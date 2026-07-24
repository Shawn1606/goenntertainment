@extends('layouts.auth')

@section('title', 'Passwort vergessen · Gönntertainment')
@section('heading', 'Kein Problem')
@section('page-title', 'Passwort zurücksetzen')

@section('content')
    <p class="mb-5 text-sm text-gray-600">
        Gib deine E-Mail-Adresse ein. Wir schicken dir einen Link, um ein neues Passwort zu setzen.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="beispiel@email.com" autocomplete="email" required class="auth-input">
        </div>

        <button type="submit" class="auth-btn-primary">Link senden</button>
    </form>

    <p class="mt-5 text-center text-sm text-gray-600">
        <a href="{{ route('welcome') }}" class="font-medium text-purple-500 hover:underline">Zurück zum Login</a>
    </p>
@endsection
