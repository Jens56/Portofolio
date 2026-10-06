<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-8">Winkelwagen</h1>
            @if(session('success'))
                <div class="bg-green-900/50 border border-green-600/50 text-green-300 px-4 py-3 rounded-lg mb-6 flex items-center gap-2">
                    <i class="las la-check-circle text-xl"></i>
                    {{ session('success') }}
                </div>
            @endif
            @if(empty($cart))
                <div class="flex flex-col items-center justify-center py-24 text-center">
                    <i class="las la-shopping-cart text-6xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400 text-lg mb-6">Je winkelwagen is leeg.</p>
                    <a href="{{ route('games') }}" class="bg-gradient-to-r from-yellow-500 to-yellow-400 text-black font-semibold px-6 py-3 rounded-lg hover:from-yellow-600 hover:to-yellow-500 transition-all">
                        Bekijk games
                    </a>
                </div>
            @else
                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    <div class="flex-1 flex flex-col gap-4">
                        @foreach($cart as $gameId => $item)
                            <div class="bg-gray-800/60 border border-gray-700 rounded-xl px-5 py-4 flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex-1">
                                    <p class="text-white font-semibold text-base md:text-lg">{{ $item['name'] }}</p>
                                    <p class="text-yellow-400 font-bold text-sm mt-1">€{{ number_format($item['price'], 2) }} per stuk</p>
                                </div>
                                <form action="{{ route('cart.update', $gameId) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    <label class="text-gray-400 text-sm">Aantal:</label>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                        class="w-16 bg-gray-900 border border-gray-600 text-white rounded-lg px-2 py-1 text-center text-sm focus:outline-none focus:border-yellow-500"
                                        onchange="this.form.submit()">
                                </form>
                                <p class="text-yellow-400 font-bold text-base whitespace-nowrap">
                                    €{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </p>
                                <form action="{{ route('cart.remove', $gameId) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="text-gray-500 hover:text-red-400 transition-colors duration-200 text-xl">
                                        <i class="las la-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                    <div class="w-full lg:w-72 bg-gray-800/60 border border-gray-700 rounded-xl px-6 py-6 flex flex-col gap-4">
                        <h2 class="text-white font-bold text-lg border-b border-gray-700 pb-3">Overzicht</h2>

                        <div class="flex justify-between text-gray-400 text-sm">
                            <span>Subtotaal</span>
                            <span class="text-white">€{{ number_format($totalPrice, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-400 text-sm">
                            <span>BTW (0%)</span>
                            <span class="text-white">€0,00</span>
                        </div>

                        <div class="border-t border-gray-700 pt-3 flex justify-between items-center">
                            <span class="text-white font-semibold">Totaal</span>
                            <span class="text-2xl font-bold text-yellow-400">€{{ number_format($totalPrice, 2) }}</span>
                        </div>

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-yellow-500 to-yellow-400 hover:from-yellow-600 hover:to-yellow-500 text-black font-bold px-6 py-3 rounded-lg transition-all duration-200 shadow-lg">
                                Afrekenen
                            </button>
                        </form>

                        <a href="{{ route('games') }}" class="text-center text-gray-500 hover:text-gray-300 text-sm transition">
                            Verder winkelen
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layout>