<div>
    @if($highscores->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-indigo-100 to-blue-100 border-b-2 border-indigo-300">
                        <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">🏅 Rank</th>
                        @if($showUsername ?? false)
                            <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">👤 Speler</th>
                        @endif
                        <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">Pogingen</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">⭐ Score</th>
                        <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">⏱️ Tijd (s)</th>
                        @if($showDeleteButton ?? false)
                            <th class="px-6 py-3 text-left text-sm font-bold text-indigo-900">Acties</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    
                    @foreach($highscores as $index => $highscore)
                        <tr onclick="window.location='{{ route('score.show', $highscore->id) }}'" class="cursor-pointer hover:bg-indigo-50 transition">
                            <a href="{{ route('score.show', ['id' => $highscore->id]) }}" class="block"></a>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-indigo-400 to-indigo-600 text-white font-bold text-sm">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            @if($showUsername ?? false)
                                <td class="px-6 py-4 text-gray-700 font-semibold">{{ $highscore->user->name ?? 'Unknown' }}</td>
                            @endif
                            <td class="px-6 py-4 text-gray-700">{{ $highscore->attempts }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-800 font-bold rounded-full text-sm">
                                    {{ $highscore->score }} pts
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 font-semibold">{{ $highscore->time_taken }}s</td>
                            @if($showDeleteButton ?? false)
                                <td class="px-6 py-4">
                                    <form method="POST" action="/highscore/{{ $highscore->id }}/delete" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hover:cursor-pointer text-red-600 hover:text-red-800 font-bold" onclick="return confirm('Zeker weten? Dit kan niet ongedaan gemaakt worden.')">
                                            Verwijderen
                                        </button>
                                    </form>
                                </td>
                            @endif
                            </a>
                        </tr>
                    </a>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <p class="text-lg">Nog geen scores... speel het spel en kom op het bord!</p>
        </div>
    @endif
</div>