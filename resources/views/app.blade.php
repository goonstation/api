<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta inertia name="title" content="{{ $page['props']['meta']['title'] }}" />
        <meta inertia name="description" content="{{ $page['props']['meta']['description'] }}">

        <meta property="og:type" content="website" />
        <meta inertia property="og:title" content="{{ $page['props']['meta']['title'] }}">
        <meta inertia property="og:description" content="{{ $page['props']['meta']['description'] }}">
        <meta inertia property="og:image" content="{{ $page['props']['meta']['image'] }}">
        <meta inertia property="og:url" content="{{ $page['props']['meta']['url'] }}">

        <meta property="twitter:card" content="summary_large_image" />
        <meta inertia property="twitter:url" content="{{ $page['props']['meta']['url'] }}" />
        <meta inertia property="twitter:title" content="{{ $page['props']['meta']['title'] }}" />
        <meta inertia property="twitter:description" content="{{ $page['props']['meta']['description'] }}" />
        <meta inertia property="twitter:image" content="{{ $page['props']['meta']['image'] }}" />

        <title inertia>{{ $page['props']['meta']['title'] }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
