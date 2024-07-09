<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Alex Younger') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,200;0,300;0,400;0,600;0,700;0,900;1,200;1,300;1,400;1,600;1,700&display=swap" rel="stylesheet">
    {{--
    <link href="https://fonts.googleapis.com/css?family=Alegreya+Sans:400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Alegreya+SC&display=swap" rel="stylesheet"> --}}

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-FGEFY4H1WF"></script>
<script>
    window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-FGEFY4H1WF');
</script>

<body class="font-sans antialiased bg-stone-50">
    <div id="terrain-container">
        <?xml version="1.0"?>
        <svg id="terrain" xmlns="http://www.w3.org/2000/svg" version="1.2"></svg>
    </div>
    @inertia
</body>

</html>

<style>
    a,
    p,
    small,
    span,
    div {
        font-family: 'Titillium Web', sans-serif;
        font-weight: 400;
    }

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