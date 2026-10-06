<x-admin.layout>
    <div class="flex pr-8 justify-between mb-4">
        <h1 class="text-4xl ml-10 font-bold text-white">Games</h1>
        <a href="/admin/games/create" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">+ Nieuwe game toevoegen</a>
    </div>
    <div class="grid gap-4 md:gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4 px-10">
        @foreach ($games as $game)
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-lg shadow-lg overflow-hidden border border-gray-700">
                <img src="{{ $game->image ? Storage::url($game->image) : asset('images/placeholder.jpg') }}" alt="{{ $game->name }}" class="w-full h-auto">
                <div class="p-3">
                    <h3 class="text-white font-bold text-sm md:text-base">{{ $game->name }}</h3>
                    <p class="text-gray-400 text-xs md:text-sm mb-3">€{{ number_format($game->price, 2) }}</p>
                    <div class="flex gap-2">
                        <a href="{{ route('admin.games.edit', $game->id) }}" class="flex-1 text-center bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-2 rounded">
                            Bewerken
                        </a>
                        <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" class="flex-1"
                            onsubmit="return confirm ('weet je zeker dat je {{ $game->name }} wilt verwijderen?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full cursor-pointer bg-red-500 hover:bg-red-600 text-white text-xs font-bold py-2 rounded">
                                Verwijderen
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-admin.layout>