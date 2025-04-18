<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
</head>
<body class="bg-white antialiased h-full">
    <div class="w-full h-full">
        <div class="absolute left-0 right-0 top-1/4">
            <div class="w-full">
				<p class="text-center text-2xl -mb-12 font-bold">Neuron Activation</p>
                <img class="mx-auto w-42 pb-6" src="/images/monkey-brain.png.webp"/>
            </div>
            <h1 class="text-center text-5xl text-gray-900">
                We'll be right back.
            </h1>
        </div>
    </div>
</body>
</html>