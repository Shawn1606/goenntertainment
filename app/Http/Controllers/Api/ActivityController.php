<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreActivityRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    private const WITH = ['host:id,name,username', 'interests:id,name,slug,icon', 'participants:id,name,username'];

    public function index(): JsonResponse
    {
        $activities = Activity::query()
            ->with(self::WITH)
            ->orderBy('starts_at')
            ->get()
            ->map(fn (Activity $activity) => $this->transform($activity))
            ->all();

        return response()->json(['data' => $activities]);
    }

    public function show(Activity $activity): JsonResponse
    {
        $activity->load(self::WITH);

        return response()->json(['data' => $this->transform($activity)]);
    }

    public function store(StoreActivityRequest $request): JsonResponse
    {
        $bannerPath = $request->hasFile('banner')
            ? $request->file('banner')->store('banners', 'public')
            : null;

        $activity = Activity::query()->create([
            'user_id' => $request->user()->id,
            'title' => $request->string('title')->toString(),
            'description' => $request->string('description')->toString(),
            'location' => $request->string('location')->toString(),
            'starts_at' => $request->date('starts_at'),
            'banner_path' => $bannerPath,
        ]);

        if ($request->filled('interests')) {
            $activity->interests()->sync($request->input('interests'));
        }

        $activity->load(self::WITH);

        return response()->json(['data' => $this->transform($activity)], 201);
    }

    /**
     * Der eingeloggte User tritt der Aktivität bei (idempotent).
     */
    public function join(Request $request, Activity $activity): JsonResponse
    {
        $activity->participants()->syncWithoutDetaching([$request->user()->id]);

        $activity->load(self::WITH);

        return response()->json(['data' => $this->transform($activity)]);
    }

    /**
     * Der eingeloggte User verlässt die Aktivität wieder.
     */
    public function leave(Request $request, Activity $activity): JsonResponse
    {
        $activity->participants()->detach($request->user()->id);

        $activity->load(self::WITH);

        return response()->json(['data' => $this->transform($activity)]);
    }

    /**
     * @return array<string, mixed>
     */
    private function transform(Activity $activity): array
    {
        return [
            'id' => $activity->id,
            'title' => $activity->title,
            'description' => $activity->description,
            'location' => $activity->location,
            'starts_at' => $activity->starts_at?->toIso8601String(),
            'banner_url' => $activity->banner_path !== null
                ? Storage::disk('public')->url($activity->banner_path)
                : null,
            'host' => $activity->host !== null ? [
                'id' => $activity->host->id,
                'name' => $activity->host->name,
                'username' => $activity->host->username,
            ] : null,
            'interests' => $activity->interests
                ->map(fn ($interest) => [
                    'id' => $interest->id,
                    'name' => $interest->name,
                    'icon' => $interest->icon,
                ])
                ->all(),
            'participants' => $activity->participants
                ->map(fn ($participant) => [
                    'id' => $participant->id,
                    'name' => $participant->name,
                    'username' => $participant->username,
                ])
                ->all(),
            'participants_count' => $activity->participants->count(),
            'is_joined' => $activity->participants->contains('id', auth()->id()),
        ];
    }
}
