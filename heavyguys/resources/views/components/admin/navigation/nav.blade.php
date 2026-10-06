<nav style="width:220px; min-height:100vh; position:fixed; top:0; left:0;" class="bg-gray-900 border-r border-gray-700 flex flex-col py-6">
    <div class="px-5 pb-4 mb-4 border-b border-gray-700">
        <p class="text-xs text-gray-400">Ingelogd als</p>
        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
    </div>
    <div class="flex flex-col gap-1 px-3 flex-1">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
            Dashboard
        </a>
        <a href="{{ route('admin.games.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
            <i class="las la-gamepad"></i>Games
        </a>
    </div>
    <div class="px-3 mt-auto">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-gray-300 hover:bg-gray-800 hover:text-white">
                <i class="las la-long-arrow-alt-left"></i>Terug naar de website
            </a>
            <span class="block my-2 border-t border-gray-700"></span>
            <button type="submit" class="w-full cursor-pointer flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-red-400 hover:bg-gray-800">
                <i class="las la-sign-out-alt"></i>Uitloggen
            </button>
        </form>
    </div>
</nav>