<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
        <div class="pt-8 px-8 pb-8">
            <h1 class="text-5xl font-bold text-gray-800 mb-2">Guess the Number</h1>
            <p class="text-gray-600">Raad het getal!</p>
        </div>
        <div class="px-8 pb-8">
            <div class="grid grid-cols-3 gap-6 mb-8">
                <div class="col-span-2">
                    <div class="bg-white rounded-xl shadow-lg p-8">
                        <div class="mb-6">
                            <p class="text-gray-600 text-sm mb-1">Het getal zit tussen</p>
                            <p class="text-6xl font-bold text-indigo-600">{{ $min }} - {{ $max }}</p>
                        </div>
                        
                        <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
                            <p class="text-gray-700">Pogingen over: <span class="font-bold text-lg text-indigo-600">{{ auth()->user()->attempts }}</span></p>
                        </div>
                        <div class="mb-8 p-4 bg-indigo-50 rounded-lg">
                            <p class="text-gray-700">Rondes over: <span class="font-bold text-lg text-indigo-600">{{ auth()->user()->rounds }}</span></p>
                        </div>
                        <form method="POST" action="/check-number" id="check-form" class="mb-6">
                            @csrf
                            <input type="text" name="guess" id="guess-input" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-lg" placeholder="Voer je getal in..." {{ auth()->user()->rounds <= 0 || auth()->user()->attempts <= 0 ? 'disabled' : '' }}>
                        </form>
                        <div class="flex gap-4">
                            <button type="submit" form="check-form" class="flex-1 bg-gradient-to-r from-green-400 to-green-600 hover:from-green-500 hover:to-green-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105 disabled:from-gray-400 disabled:to-gray-500 hover:cursor-pointer disabled:cursor-not-allowed" {{ auth()->user()->rounds <= 0 || auth()->user()->attempts <= 0 ? 'disabled' : '' }}>
                                Controleren
                            </button>
                            <a href="/settings" class="flex-1 bg-gradient-to-r from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center justify-center text-center">
                                Nieuw Spel
                            </a>
                        </div>
                        @if(session('correct'))
                            <div class="mt-6 p-4 bg-green-100 border-l-4 border-green-500 rounded-lg">
                                <p class="text-green-700 font-bold text-lg">🎉 {{ session('correct') }}</p>
                            </div>
                        @endif
                        @if(session('hint'))
                            <div class="mt-6 p-4 bg-amber-100 border-l-4 border-amber-500 rounded-lg">
                                <p class="text-amber-700 font-bold">💡 {{ session('hint') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-span-1">
                    <div id="countdown-container" class="bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl shadow-lg p-8 text-white h-full flex flex-col justify-center items-center">
                        <p class="text-lg font-semibold mb-4 opacity-90">Tijd over:</p>
                        <p id="countdown" class="text-7xl font-bold">{{ $timeRemaining }}</p>
                        <p class="text-sm mt-4 opacity-75">seconden</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-6">🏆 Scoreboards</h2>               
                <div class="tab-wrapper">
                    <input type="radio" name="scoretab" id="tab-global" class="hidden" checked>
                    <input type="radio" name="scoretab" id="tab-personal" class="hidden">
                    <div class="flex border-b border-gray-200 mb-6">
                        <label for="tab-global" class="tab-label cursor-pointer px-6 py-3 font-bold text-gray-600 border-b-2 border-transparent hover:text-indigo-600 transition">
                            🌍 Globaal
                        </label>
                        <label for="tab-personal" class="tab-label cursor-pointer px-6 py-3 font-bold text-gray-600 border-b-2 border-transparent hover:text-indigo-600 transition">
                            👤 Mijn Scores
                        </label>
                    </div>
                    <div class="tab-content block">
                        <h3 class="text-xl font-bold text-gray-700 mb-4">Alle Spelers</h3>
                        <x-scorelist :highscores="$globalHighscores" :showUsername="true" />
                    </div>
                    <div class="tab-content hidden">
                        <h3 class="text-xl font-bold text-gray-700 mb-4">Jouw Best Scores</h3>
                        <x-scorelist :highscores="$personalHighscores" :showDeleteButton="true" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>