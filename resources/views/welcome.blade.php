<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Willkommen bei Gönntertainment</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
{{-- Startseite: oben der Willkommens-Screen, unten fährt per Hochwischen/Klick das Login-Sheet rein. --}}
<body class="goenn-bg font-sans antialiased text-gray-800">
    <div class="relative mx-auto flex min-h-dvh max-w-lg flex-col overflow-hidden px-6 py-10 sm:max-w-md">

        {{-- Willkommens-Bereich --}}
        <div class="flex flex-1 flex-col items-center justify-center text-center">
            <p class="mb-2 text-2xl font-medium text-gray-700">Willkommen bei</p>
            <h1 class="mb-10 text-4xl font-bold tracking-tight">
                <span class="brand-gradient-text">Gönntertainment</span>
            </h1>

            <p class="mb-6 text-lg font-medium text-gray-600">Lege direkt los!</p>

            <div class="mb-10 flex justify-center">
                <x-auth-illustration />
            </div>

            <button
                type="button"
                data-sheet-open
                class="auth-btn-primary max-w-xs"
            >
                Anmelden
            </button>
            <p class="mt-3 text-sm text-gray-500">oder nach oben wischen</p>
        </div>

        {{-- Griff unten, deutet das Sheet an --}}
        <button
            type="button"
            data-sheet-open
            aria-label="Anmelden öffnen"
            class="mx-auto mb-1 flex flex-col items-center gap-1 pb-2 text-gray-400"
        >
            <span class="h-1.5 w-12 rounded-full bg-gray-300"></span>
        </button>
    </div>

    {{-- Abdunkelnder Hintergrund --}}
    <div
        data-sheet-backdrop
        class="pointer-events-none fixed inset-0 z-40 bg-black/30 opacity-0 transition-opacity duration-300"
    ></div>

    {{-- Login-Sheet: fährt von unten hoch --}}
    <div
        data-sheet
        class="fixed inset-x-0 bottom-0 z-50 mx-auto w-full max-w-lg translate-y-full transition-transform duration-300 ease-out sm:max-w-md"
    >
        <div class="rounded-t-3xl border border-white/60 bg-white/95 px-6 pb-8 pt-3 shadow-2xl backdrop-blur-sm">
            {{-- Ziehgriff zum Schließen --}}
            <button
                type="button"
                data-sheet-close
                aria-label="Schließen"
                class="mx-auto mb-4 block h-1.5 w-12 rounded-full bg-gray-300"
            ></button>

            <h2 class="mb-6 text-center text-2xl font-bold tracking-tight">Willkommen zurück!</h2>

            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                {{-- E-Mail --}}
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l9 6 9-6M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="beispiel@email.com"
                        autocomplete="email"
                        required
                        class="auth-input"
                    >
                </div>

                {{-- Passwort --}}
                <div class="relative">
                    <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    <input
                        type="password"
                        name="password"
                        placeholder="Dein Passwort"
                        autocomplete="current-password"
                        required
                        class="auth-input"
                    >
                </div>

                {{-- Passwort speichern + vergessen --}}
                <div class="flex items-center justify-between px-1">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="h-4 w-4 rounded border-gray-300 text-purple-500 focus:ring-purple-300">
                        Passwort speichern?
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-purple-500 hover:underline">
                        Passwort vergessen?
                    </a>
                </div>

                <button type="submit" class="auth-btn-primary">Anmelden</button>
            </form>

            <p class="mt-4 text-center text-sm text-gray-600">
                Du hast kein Account?
                <a href="{{ route('register') }}" class="font-medium text-purple-500 hover:underline">Jetzt Registrieren</a>
            </p>

            {{-- Trenner --}}
            <div class="my-5 flex items-center gap-3 text-xs text-gray-400">
                <span class="h-px flex-1 bg-gray-200"></span>
                oder
                <span class="h-px flex-1 bg-gray-200"></span>
            </div>

            {{-- Google-Login --}}
            <a href="{{ route('google.redirect') }}" class="auth-btn-google">
                <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#EA4335" d="M12 10.2v3.9h5.5c-.24 1.4-1.7 4.1-5.5 4.1-3.3 0-6-2.7-6-6s2.7-6 6-6c1.9 0 3.1.8 3.8 1.5l2.6-2.5C16.9 3.1 14.7 2 12 2 6.9 2 2.8 6.1 2.8 11.2S6.9 20.4 12 20.4c5.9 0 9.8-4.1 9.8-9.9 0-.7-.1-1.2-.2-1.7H12z"/></svg>
                Mit Google anmelden
            </a>
        </div>
    </div>

    <script>
        (function () {
            const sheet = document.querySelector('[data-sheet]');
            const backdrop = document.querySelector('[data-sheet-backdrop]');
            if (!sheet || !backdrop) return;

            function openSheet() {
                sheet.classList.remove('translate-y-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
            }
            function closeSheet() {
                sheet.classList.add('translate-y-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
            }

            document.querySelectorAll('[data-sheet-open]').forEach(el => el.addEventListener('click', openSheet));
            document.querySelectorAll('[data-sheet-close]').forEach(el => el.addEventListener('click', closeSheet));
            backdrop.addEventListener('click', closeSheet);

            // Hochwischen auf der Startseite öffnet, Runterwischen im Sheet schließt.
            let startY = null;
            document.addEventListener('touchstart', e => { startY = e.touches[0].clientY; }, { passive: true });
            document.addEventListener('touchend', e => {
                if (startY === null) return;
                const dy = e.changedTouches[0].clientY - startY;
                if (dy < -60) openSheet();
                if (dy > 60) closeSheet();
                startY = null;
            }, { passive: true });

            // Bei Login-Fehlern oder Status-Meldung direkt geöffnet zeigen.
            @if ($errors->any() || session('status') || old('email'))
                openSheet();
            @endif
        })();
    </script>
</body>
</html>
