<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gönntertainment')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="goenn-bg font-sans antialiased text-gray-800">
    <div class="mx-auto flex min-h-dvh max-w-lg flex-col px-4 py-8 sm:max-w-md sm:px-6">
        <div class="mb-6 text-center">
            @hasSection('heading')
                <p class="mb-1 text-sm text-gray-600">@yield('heading')</p>
            @endif
            <h1 class="text-3xl font-bold tracking-tight">
                @hasSection('brand-only')
                    <span class="brand-gradient-text">Gönntertainment</span>
                @else
                    @yield('page-title')
                @endif
            </h1>
        </div>

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

        <div class="auth-card mb-8">
            @yield('content')
        </div>

        <div class="flex flex-1 flex-col items-center justify-end pb-4">
            <x-auth-illustration />
        </div>
    </div>
</body>
</html>
