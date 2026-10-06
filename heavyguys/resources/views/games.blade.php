<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-8">Onze games</h1>
            <div class="relative w-full md:w-4/5 mx-auto mb-12 rounded-xl overflow-hidden shadow-2xl border border-gray-700">
                <img src="/images/HorCover.png" alt="Games Banner" class="w-full h-auto max-h-96 object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
            </div>
            <div class="grid gap-4 md:gap-6 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @foreach ($games as $game)
                    <a href="{{ route('game.show', $game->id) }}"
                       class="group bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-lg overflow-hidden border border-gray-700 hover:border-yellow-500/60 hover:shadow-yellow-900/20 hover:shadow-xl transition-all duration-300">
                        <div class="relative overflow-hidden">
                            <img src="{{ $game->image ? Storage::url($game->image) : asset('images/placeholder.jpg') }}"
                                 alt="{{ $game->name }}"
                                 class="w-full h-auto object-cover group-hover:scale-105 group-hover:blur-sm transition-all duration-300">
                            <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <h3 class="text-white font-bold text-sm md:text-base text-center">{{ $game->name }}</h3>
                                <p class="text-gray-300 text-xs md:text-sm text-center mt-2 line-clamp-3">{{ Str::limit($game->description, 80) }}</p>
                                <span class="mt-3 text-xs font-semibold text-yellow-400 border border-yellow-500/40 px-3 py-1 rounded-full">
                                    Bekijk game 
                                </span>
                            </div>
                        </div>
                        <div class="px-3 py-2 flex items-center justify-between">
                            <p class="text-white text-xs md:text-sm font-semibold truncate">{{ $game->name }}</p>
                            <p class="text-yellow-400 text-xs md:text-sm font-bold ml-2 whitespace-nowrap">€{{ number_format($game->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-layout>