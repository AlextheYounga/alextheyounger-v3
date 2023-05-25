<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @if(request()->is('/'))
        <div id="terrain-container">
            <?xml version="1.0"?>
            <svg id="terrain" xmlns="http://www.w3.org/2000/svg" version="1.2"></svg>
        </div>
    @endif
    @inertia
</body>

</html>

<style>
    #terrain-container {
        position: fixed;
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }

    #terrain-container #terrain {
        width: 100vw;
        height: 100vh;
        max-height: 100vh;
        max-width: 100vw;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
    }
</style>