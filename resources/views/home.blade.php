<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Tailwind</title>
    @vite('resources/css/app.css')
</head>
<body class="">
    <div class="font-extrabold  text-5xl text-yellow-500 justify-center">
        Hello World!
    </div>
</body>
</html>
