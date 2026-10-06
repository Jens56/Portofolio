<x-admin.layout>
    <div class="pl-7 pr-8 py-6">
        <h1 class="text-3xl font-bold text-white mb-8">Dashboard</h1>

        @php
            $totalGames = \App\Models\Games::count();
            $totalUsers = \App\Models\User::count();
            $totalAdmins = \App\Models\User::where('is_admin', true)->count();
            $recentGames = \App\Models\Games::latest()->take(5)->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm mb-1">Totaal games</p>
                <p class="text-3xl font-bold text-white">{{ $totalGames }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm mb-1">Totaal gebruikers</p>
                <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
            </div>
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <p class="text-gray-400 text-sm mb-1">Admins</p>
                <p class="text-3xl font-bold text-white">{{ $totalAdmins }}</p>
            </div>
        </div>
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-white mb-4">Snelkoppelingen</h2>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('admin.games.index') }}"
                   class="bg-gray-800 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg font-medium transition">
                    Beheer games
                </a>
            </div>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-white mb-4">Recent toegevoegde games</h2>
            @if($recentGames->isEmpty())
                <p class="text-gray-500">Nog geen games toegevoegd.</p>
            @else
                <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-400 text-sm">
                                <th class="px-6 py-3">Naam</th>
                                <th class="px-6 py-3">Prijs</th>
                                <th class="px-6 py-3">Toegevoegd</th>
                                <th class="px-6 py-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentGames as $game)
                                <tr class="border-b border-gray-800 last:border-0 text-white">
                                    <td class="px-6 py-3">{{ $game->name }}</td>
                                    <td class="px-6 py-3">€{{ number_format($game->price, 2) }}</td>
                                    <td class="px-6 py-3 text-gray-400">{{ $game->created_at->diffForHumans() }}</td>
                                    <td class="px-6 py-3 text-right">
                                        <a href="{{ route('admin.games.edit', $game) }}" class="text-blue-400 hover:underline">
                                            Bewerken
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-admin.layout>