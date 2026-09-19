<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kunjungan')</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-corn-cream min-h-screen flex items-center justify-center p-4 text-corn-dark">
    <div class="w-full max-w-md">
        @yield('content')
    </div>
</body>
</html>