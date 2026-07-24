<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home · Gönntertainment</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="goenn-bg font-sans antialiased text-gray-800">
    <div class="mx-auto flex min-h-dvh max-w-lg flex-col px-4 py-8 sm:max-w-md sm:px-6">

        {{-- Kopf --}}
        <div class="mb-6 flex items-center justify-between">
            <span class="text-xl font-bold tracking-tight brand-gradient-text">Gönntertainment</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-2xl border border-gray-200 bg-white/80 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Abmelden
                </button>
            </form>
        </div>

        {{-- Begrüßung --}}
        <div class="auth-card mb-6">
            <h1 class="text-2xl font-bold tracking-tight">Hallo {{ $user->name }} 👋</h1>
            <p class="mt-1 text-sm text-gray-600">@{{ $user->username }}</p>

            <div class="mt-4 flex flex-wrap items-center gap-2 text-sm">
                <span class="rounded-full bg-purple-50 px-3 py-1 font-medium text-purple-700">
                    {{ $user->account_type?->label() ?? 'Konto' }}
                </span>
            </div>

            @if ($user->interests->isNotEmpty())
                <div class="mt-4">
                    <p class="mb-2 text-sm font-medium text-gray-700">Deine Interessen</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($user->interests as $interest)
                            <span class="rounded-2xl border border-purple-100 bg-white/70 px-3 py-1 text-sm text-gray-700">
                                {{ $interest->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="flex flex-1 flex-col items-center justify-end pb-4">
            <x-auth-illustration />
        </div>
    </div>
</body>
</html>
