@extends('layouts.auth')

@section('title', 'Registrieren · Gönntertainment')
@section('heading', 'Schön, dass du dabei bist')
@section('page-title', 'Konto erstellen')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </span>
            <input type="text" name="name" value="{{ old('name') }}" placeholder="Dein Name" autocomplete="name" required class="auth-input">
        </div>

        {{-- Benutzername --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">@</span>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="benutzername" autocomplete="username" required class="auth-input">
        </div>

        {{-- E-Mail --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="beispiel@email.com" autocomplete="email" required class="auth-input">
        </div>

        {{-- Passwort --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <input type="password" name="password" placeholder="Passwort (min. 8 Zeichen, Buchstaben & Zahlen)" autocomplete="new-password" required class="auth-input">
        </div>

        {{-- Passwort bestätigen --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </span>
            <input type="password" name="password_confirmation" placeholder="Passwort wiederholen" autocomplete="new-password" required class="auth-input">
        </div>

        {{-- Konto-Typ --}}
        <div>
            <p class="mb-2 text-sm font-medium text-gray-700">Konto-Typ</p>
            <div class="flex gap-3">
                @foreach (\App\AccountType::cases() as $type)
                    <label class="account-type-option">
                        <input type="radio" name="account_type" value="{{ $type->value }}" class="sr-only"
                            @checked(old('account_type', \App\AccountType::Personal->value) === $type->value)>
                        <span class="font-medium">{{ $type->label() }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        {{-- Interessen --}}
        <div>
            <p class="mb-2 text-sm font-medium text-gray-700">Interessen <span class="text-gray-400">(mind. 3)</span></p>
            @if ($interests->isEmpty())
                <p class="text-sm text-gray-500">Noch keine Interessen verfügbar.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach ($interests as $interest)
                        <label class="interest-chip">
                            <input type="checkbox" name="interests[]" value="{{ $interest->id }}" class="sr-only"
                                @checked(in_array($interest->id, old('interests', [])))>
                            {{ $interest->name }}
                        </label>
                    @endforeach
                </div>
            @endif
        </div>

        <button type="submit" class="auth-btn-primary">Konto erstellen</button>
    </form>

    <p class="mt-5 text-center text-sm text-gray-600">
        Schon ein Konto?
        <a href="{{ route('welcome') }}" class="font-medium text-purple-500 hover:underline">Zum Login</a>
    </p>
@endsection
