<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <a href="{{ route('games') }}" class="text-blue-400 hover:text-blue-300 mb-8 inline-flex items-center gap-2 text-sm md:text-base transition">
                <i class="las la-arrow-left"></i> Terug naar games
            </a>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-16 items-start mt-6">
                <div class="flex justify-center">
                    <img src="{{ $game->image ? Storage::url($game->image) : asset('images/placeholder.jpg') }}"
                         alt="{{ $game->name }}"
                         class="w-full max-w-sm h-auto rounded-xl shadow-2xl border border-gray-700 object-cover">
                </div>
                <div class="flex flex-col gap-4">
                    <h1 class="text-3xl md:text-4xl font-bold text-white">{{ $game->name }}</h1>
                    @auth
                        @if(auth()->user()->purchases->contains('game_id', $game->id))
                            <span class="inline-flex items-center gap-2 text-green-400 font-semibold text-lg">
                                <i class="las la-check-circle text-xl"></i> In bezit
                            </span>
                        @else
                            <p class="text-2xl md:text-3xl font-bold text-yellow-400">€{{ number_format($game->price, 2) }}</p>
                        @endif
                    @endauth
                    @guest
                        <p class="text-2xl md:text-3xl font-bold text-yellow-400">€{{ number_format($game->price, 2) }}</p>
                    @endguest
                    <div class="border-t border-gray-700 my-1"></div>
                    <p class="text-gray-300 text-sm md:text-base leading-relaxed">
                        {!! nl2br(e($game->description)) !!}
                    </p>
                    <div class="border-t border-gray-700 my-1"></div>
                    <div class="flex flex-col sm:flex-row gap-3 pt-1">
                        @auth
                            @if(auth()->user()->purchases->contains('game_id', $game->id))
                                <a href="{{ route('game.download', $game->id) }}"
                                class="hidden md:inline-flex items-center justify-center gap-2 bg-gradient-to-r from-green-600 to-green-500 hover:from-green-700 hover:to-green-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-lg">
                                    <i class="las la-download"></i> Downloaden
                                </a>
                            @else
                                <form action="{{ route('cart.add', $game->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-lg cursor-pointer">
                                        <i class="las la-shopping-cart"></i> Toevoegen aan winkelmand
                                    </button>
                                </form>
                            @endif
                        @endauth
                        @guest
                            <a href="{{ route('login') }}"
                               class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold px-6 py-3 rounded-lg transition-all duration-200 shadow-lg">
                                <i class="las la-shopping-cart"></i> Log in om te kopen
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>