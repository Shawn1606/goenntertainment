@extends('layouts.auth')

@section('title', 'Neues Passwort · Gönntertainment')
@section('heading', 'Fast geschafft')
@section('page-title', 'Neues Passwort setzen')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        {{-- E-Mail --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <input type="email" name="email" value="{{ old('email', $email) }}" placeholder="beispiel@email.com" autocomplete="email" required class="auth-input">
        </div>

        {{-- Neues Passwort --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <input type="password" name="password" placeholder="Neues Passwort (min. 8 Zeichen, Buchstaben & Zahlen)" autocomplete="new-password" required class="auth-input">
        </div>

        {{-- Bestätigen --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <input type="password" name="password_confirmation" placeholder="Passwort wiederholen" autocomplete="new-password" required class="auth-input">
        </div>

        <button type="submit" class="auth-btn-primary">Passwort speichern</button>
    </form>
@endsection
