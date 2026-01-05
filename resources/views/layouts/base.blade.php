<!DOCTYPE html>
<html lang="{{ Str::replace('_', '-', app()->getLocale()) }}" >
<head>
    <meta charset="UTF-8">
    <title>{{ isset($title) ? $title . ' :: ' . config('app.name') : config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="preload" href="/logo.svg" as="image" media="(prefers-color-scheme: light)" />
    <link rel="preload" href="/logo-dark.svg" as="image" media="(prefers-color-scheme: dark)" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('scripts')
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">
    @yield('content')

    <x-toast />
</body>
</html>
