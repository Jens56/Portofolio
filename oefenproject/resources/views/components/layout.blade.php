<!DOCTYPE html>
<html lang="en" data-theme="lofi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - ToDo' : 'ToDo' }}</title>
    <link rel="preconnect" href="<https://fonts.bunny.net>">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen min-w-screen bg-gray-100 flex flex-col overflow-x-hidden">
    <nav class="flex gap-4 bg-blue-500 justify-between">
        <div class="">
            <a href="/" class="btn btn-ghost text-xl text-bold tracking-tight text-white">To do list</a>
        </div>
        <div class="p-1">
            @auth
                <span class="text-sm">{{ auth()->user()->name }}</span>
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="btn ml-3 mr-5 text-black bg-white hover:bg-gray-300  btn-sm">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn text-black bg-white hover:bg-gray-300  btn-sm">Sign In</a>
                <a href="{{ route('register') }}" class="btn text-black bg-white hover:bg-gray-300  btn-sm">Sign Up</a>
            @endauth
        </div>
    </nav>

    @if (session('success'))
        <div class="toast toast-top toast-center">
            <div class="alert alert-success animate-fade-out">
                <svg xmlns="<http://www.w3.org/2000/svg>" class="h-6 w-6 shrink-0 stroke-current" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    <div class="flex flex-1">
        <nav class="w-64 bg-white shadow-md">
            <div class="grid grid-cols-1">
                <a href="/day" class="h-16 px-8 pt-5 {{ request()->is('day') ? 'bg-gray-200 shadow-lg border-l-4 border-blue-500' : 'bg-white hover:bg-gray-200 hover:shadow-lg shadow-md' }}">mijn dag</a>
                <a href="/important" class="h-16 px-8 pt-5 {{ request()->is('important') ? 'bg-gray-200 shadow-lg border-l-4 border-blue-500' : 'bg-white hover:bg-gray-200 hover:shadow-lg shadow-md' }}">belangrijk</a>
                <a href="/planned" class="h-16 px-8 pt-5 {{ request()->is('planned') ? 'bg-gray-200 shadow-lg border-l-4 border-blue-500' : 'bg-white hover:bg-gray-200 hover:shadow-lg shadow-md' }}">gepland</a>
                <a href="/gotSend" class="h-16 px-8 pt-5 {{ request()->is('gotSend') ? 'bg-gray-200 shadow-lg border-l-4 border-blue-500' : 'bg-white hover:bg-gray-200 hover:shadow-lg shadow-md' }}">toegewezen aan mij</a>
                <a href="/tasks" class="h-16 px-8 pt-5 {{ request()->is('tasks') ? 'bg-gray-200 shadow-lg border-l-4 border-blue-500' : 'bg-white hover:bg-gray-200 hover:shadow-lg shadow-md' }}">taken</a>
            </div>
        </nav>
        <main class="flex p-8">
            {{ $slot }}
        </main>
    </div>
    <footer class="text-center bg-blue-500 text-white fixed bottom-0 w-full">
        <div>
            <p>© {{ date('Y') }} ToDo - Oefen project</p>
        </div>
    </footer>
</body>

</html>