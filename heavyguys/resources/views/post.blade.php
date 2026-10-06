<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex flex-col">
        <div class="flex-grow px-5 md:px-12 pt-10 pb-20">
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Nieuw bericht</h1>
                <p class="text-gray-300 text-sm md:text-base">Vul de titel en inhoud in en klik op 'Opslaan'</p>
            </div>
            <div class="bg-gradient-to-br from-gray-800 to-gray-900 p-6 md:p-8 rounded-xl border border-gray-700 max-w-2xl mx-auto">
                <form action="/post" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="title" class="block text-white text-sm font-bold mb-2">Titel:</label>
                        <input type="text" id="title" name="title" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="content" class="block text-white text-sm font-bold mb-2">Inhoud:</label>
                        <textarea id="content" name="content" rows="6" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" style="overflow-wrap: break-word; word-break: break-word;" required></textarea>
                    </div>
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-blue-600 to-blue-500 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-blue-600 transition-all duration-300 font-semibold shadow-lg">Opslaan</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>