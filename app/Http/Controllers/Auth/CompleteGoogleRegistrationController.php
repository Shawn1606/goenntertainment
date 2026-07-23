<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CompleteGoogleRegistrationRequest;
use App\Models\Interest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CompleteGoogleRegistrationController extends Controller
{
    public function create(): View|RedirectResponse
    {
        $user = auth()->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if ($user->hasCompletedProfile()) {
            return redirect()->route('home');
        }

        return view('auth.complete-google-registration', [
            'interests' => Interest::query()->orderBy('name')->get(),
        ]);
    }

    public function store(CompleteGoogleRegistrationRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            'username' => $request->string('username')->toString(),
            'account_type' => $request->enum('account_type', \App\AccountType::class),
        ]);

        $user->interests()->sync($request->input('interests'));

        return redirect()->route('home');
    }
}
