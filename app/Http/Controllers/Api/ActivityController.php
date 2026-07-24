<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreActivityRequest;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class ActivityController extends Controller
{
    private const WITH = ['host:id,name,username', 'interests:id,name,slug,icon'];

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
        ];
    }
}
