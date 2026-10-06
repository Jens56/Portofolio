<x-layout>
    <div class="relative">
        <img src="/images/background.jpeg" alt="Hero Image" class="w-full h-64 md:h-96 object-cover opacity-60">
        <div class="absolute inset-0 bg-black/10 flex items-center justify-center">
            <div class="text-center text-white px-4">
                <h1 class="text-3xl md:text-5xl lg:text-6xl font-bold mb-3 md:mb-4">Heavy Guys</h1>
                <p class="text-sm md:text-lg lg:text-2xl text-gray-100 mb-6">Game winkel, blogs & community</p>
                <a href="/games" class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-black hover:from-yellow-600 hover:to-yellow-500 transition-all font-semibold px-6 py-3 rounded-lg inline-block">Explore Games</a>
            </div>
        </div>
    </div>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 py-12 md:py-20">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-8">Onze nieuwste game</h2>
            @if($latestGame)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 items-start pt-6">
                    <div class="flex justify-center">
                        <img src="{{ Storage::url($latestGame->image) }}" alt="{{ $latestGame->name }}" class="w-full max-w-sm h-auto object-cover rounded-lg shadow-lg border border-gray-700">
                    </div>
                    <div>
                        <h3 class="text-2xl md:text-3xl font-semibold text-white mb-4">{{ $latestGame->name }}</h3>
                        <div x-data="{ expanded: false }">
                            <p x-show="!expanded" class="text-gray-300 text-sm md:text-base mb-2 leading-relaxed">
                                {!! nl2br(e(Str::limit($latestGame->description, 150))) !!}
                            </p>
                            <div x-show="expanded" x-transition class="text-gray-300 text-sm md:text-base mb-2 leading-relaxed">
                                {!! nl2br(e($latestGame->description)) !!}
                            </div>
                            <button
                                @click="expanded = !expanded"
                                class="text-yellow-400 hover:text-yellow-300 text-sm font-semibold mb-6 transition">
                                <span x-text="expanded ? 'Minder lezen ▲' : 'Meer lezen ▼'"></span>
                            </button>
                        </div>
                        <a href="/games/{{ $latestGame->id }}" class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-black hover:from-yellow-600 hover:to-yellow-500 transition-all font-semibold px-6 py-3 rounded-lg inline-block">Koop nu!</a>
                    </div>
                </div>
            @else
                <p class="text-gray-400">Nog geen games beschikbaar.</p>
            @endif
        </div>
    </div>
    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 py-12 md:py-16">
        <div class="px-5 md:px-12">
            <h2 class="text-2xl md:text-3xl font-bold text-black mb-6">Check ook:</h2>
            <div class="flex flex-col gap-3">
                <a href="/games" class="text-black hover:text-gray-800 font-semibold text-base md:text-lg transition"><i class="las la-gamepad mr-2"></i>Onze games</a>
                <a href="/blog" class="text-black hover:text-gray-800 font-semibold text-base md:text-lg transition"><i class="las la-blog mr-2"></i>Onze blog</a>
            </div>
        </div>
    </div>
</x-layout>