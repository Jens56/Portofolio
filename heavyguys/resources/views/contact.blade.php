<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">Contact ons</h1>
                <p class="text-gray-300 text-sm md:text-base">Stel hier uw vragen of suggesties</p>
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 md:p-8 rounded-xl border border-gray-700 max-w-2xl mx-auto">
                <form action="/contact" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="message" class="block text-white text-sm font-bold mb-2">Uw bericht</label>
                        <textarea id="message" maxlength="500" placeholder="Mijn vraag is..." name="message" rows="6" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 resize-none" style="overflow-wrap: break-word; word-break: break-word;" required></textarea>
                        <p class="text-gray-400 text-xs mt-1">Max. 500 letters</p>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-yellow-500 to-yellow-400 text-black px-6 py-3 rounded-lg hover:from-yellow-600 hover:to-yellow-500 transition-all duration-300 font-semibold cursor-pointer shadow-lg">Bericht sturen</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>