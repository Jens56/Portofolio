<x-layout>
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-pink-100">
        <div class="pt-8 px-8 pb-8">
            <h1 class="text-5xl font-bold text-gray-800 mb-2">⚙️ Instellingen</h1>
            <p class="text-gray-600">Pas je game instellingen aan</p>
            <p class="text-sm text-gray-500 mt-2">💾 Je vorige instellingen zijn automatisch opgeslagen</p>
        </div>
        <div class="px-8 pb-8">
            <div class="max-w-2xl">
                <div class="bg-white rounded-xl shadow-lg p-8">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 rounded-lg">
                            <p class="text-green-700 font-bold">✅ {{ session('success') }}</p>
                        </div>
                    @endif

                    <form method="POST" action="/settings">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="countdown_seconds" class="block text-gray-700 font-bold mb-2 text-lg">Countdown Timer (seconden)</label>
                            <input type="number" id="countdown_seconds" name="countdown_seconds" value="{{ auth()->user()->countdown_seconds }}" min="0" max="3600" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-lg">
                            @error('countdown_seconds')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="attempts" class="block text-gray-700 font-bold mb-2 text-lg">Pogingen</label>
                            <input type="number" id="attempts" name="attempts" value="{{ auth()->user()->attempts }}" min="1" max="100" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-lg">
                            @error('attempts')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="min_number" class="block text-gray-700 font-bold mb-2 text-lg">Minimum getal</label>
                            <input type="number" id="min_number" name="min_number" value="{{ auth()->user()->min_number }}" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-lg">
                            @error('min_number')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="max_number" class="block text-gray-700 font-bold mb-2 text-lg">Maximum getal</label>
                            <input type="number" id="max_number" name="max_number" value="{{ auth()->user()->max_number }}" min="0" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-lg">
                            @error('max_number')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="rounds" class="block text-gray-700 font-bold mb-2 text-lg">Rondes</label>
                            <input type="number" id="rounds" name="rounds" value="{{ auth()->user()->rounds }}" min="1" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-200 text-lg">
                            @error('rounds')
                                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="hover:cursor-pointer w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition transform hover:scale-105">
                            Start het spel
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>