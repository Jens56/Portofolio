<x-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Score Details</h1>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Speler: {{ $highscore->user->name ?? 'Unknown' }}</h2>
            <p class="text-gray-600 mb-2"><strong>Pogingen:</strong> {{ $highscore->attempts }}</p>
            <p class="text-gray-600 mb-2"><strong>Score:</strong> {{ $highscore->score }} pts</p>
            <p class="text-gray-600 mb-4"><strong>Tijd:</strong> {{ $highscore->time_taken }} seconden</p>
            <a href="{{ route('home') }}" class="inline-block bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:scale-105">Terug naar scorebord</a>
        </div>
    </div>
</x-layout>