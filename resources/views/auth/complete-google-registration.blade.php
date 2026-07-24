@extends('layouts.auth')

@section('title', 'Profil vervollständigen · Gönntertainment')
@section('heading', 'Nur noch ein Schritt')
@section('page-title', 'Profil vervollständigen')

@section('content')
    <p class="mb-5 text-sm text-gray-600">
        Wähle einen Benutzernamen, deinen Konto-Typ und mindestens 3 Interessen.
    </p>

    <form method="POST" action="{{ route('google.complete.store') }}" class="space-y-5">
        @csrf

        {{-- Benutzername --}}
        <div class="relative">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">@</span>
            <input type="text" name="username" value="{{ old('username') }}" placeholder="benutzername" autocomplete="username" required class="auth-input">
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

        <button type="submit" class="auth-btn-primary">Weiter</button>
    </form>
@endsection
