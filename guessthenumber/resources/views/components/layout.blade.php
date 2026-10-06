<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Guess the Number</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
</head>

<body class="min-h-screen flex justify-center items-center bg-gray-200">
  <div class="relative w-3/5 h-[85vh] bg-white flex flex-col rounded-lg">
    <div class="absolute top-2 left-2 text-2xl cursor-pointer flex gap-4">
        <x-buttons.settingsIcon />
        <x-buttons.logout />
    </div>
    <main class="flex flex-col flex-1">
        {{ $slot }}
    </main>
  </div>
</body>
</html>