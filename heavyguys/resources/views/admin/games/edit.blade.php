<x-admin.layout>
    <div class="max-w-4xl mx-auto">
        <div class="bg-gray-800 rounded-2xl shadow-xl p-8 border border-gray-700">
            <h1 class="text-3xl font-bold text-white mb-8">
                Game bewerken
            </h1>

            <form method="POST" action="{{ route('admin.games.update', $game->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Naam</label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $game->name) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition"
                        placeholder="Typ de naam van de game...">
                    @error('name')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">Genre</label>
                        <select
                            name="genre"
                            class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition">
                            <option value="" disabled {{ old('genre', $game->genre) ? '' : 'selected' }}>Selecteer een genre...</option>
                            <option value="Action" {{ old('genre', $game->genre) == 'Action' ? 'selected' : '' }}>Action</option>
                            <option value="Adventure" {{ old('genre', $game->genre) == 'Adventure' ? 'selected' : '' }}>Adventure</option>
                            <option value="RPG" {{ old('genre', $game->genre) == 'RPG' ? 'selected' : '' }}>RPG</option>
                            <option value="Shooter" {{ old('genre', $game->genre) == 'Shooter' ? 'selected' : '' }}>Shooter</option>
                            <option value="Strategy" {{ old('genre', $game->genre) == 'Strategy' ? 'selected' : '' }}>Strategy</option>
                            <option value="Simulation" {{ old('genre', $game->genre) == 'Simulation' ? 'selected' : '' }}>Simulation</option>
                            <option value="Sports" {{ old('genre', $game->genre) == 'Sports' ? 'selected' : '' }}>Sports</option>
                            <option value="Racing" {{ old('genre', $game->genre) == 'Racing' ? 'selected' : '' }}>Racing</option>
                            <option value="Puzzle" {{ old('genre', $game->genre) == 'Puzzle' ? 'selected' : '' }}>Puzzle</option>
                            <option value="Horror" {{ old('genre', $game->genre) == 'Horror' ? 'selected' : '' }}>Horror</option>
                            <option value="Platformer" {{ old('genre', $game->genre) == 'Platformer' ? 'selected' : '' }}>Platformer</option>
                            <option value="Fighting" {{ old('genre', $game->genre) == 'Fighting' ? 'selected' : '' }}>Fighting</option>
                        </select>
                        @error('genre')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-300 mb-2">PEGI</label>
                        <input
                            type="text"
                            name="pegi"
                            value="{{ old('pegi', $game->pegi) }}"
                            class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="18">
                        @error('pegi')
                            <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Prijs (€)</label>
                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="{{ old('price', $game->price) }}"
                        class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition"
                        placeholder="59.99">
                    @error('price')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Beschrijving</label>
                    <textarea
                        name="description"
                        rows="5"
                        class="w-full bg-gray-900 border border-gray-600 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 outline-none transition resize-none"
                        placeholder="Beschrijving van de game..."
                    >{{ old('description', $game->description) }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Afbeelding</label>
                    @if($game->image)
                        <div class="mb-3">
                            <img src="{{ Storage::url($game->image) }}" alt="Huidige afbeelding" class="h-32 rounded-xl object-cover">
                            <p class="text-gray-400 text-xs mt-1">Huidige afbeelding — upload een nieuwe om te vervangen</p>
                        </div>
                    @endif
                    <input
                        type="file"
                        name="image"
                        accept="image/*"
                        class="block w-full text-sm text-gray-300
                        file:mr-4 file:py-3 file:px-4
                        file:rounded-xl file:border-0
                        file:bg-blue-600 file:text-white
                        file:font-medium
                        hover:file:bg-blue-700 cursor-pointer">
                    @error('image')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-300 mb-2">Game bestand (.zip)</label>
                    @if($game->game_file)
                        <p class="text-gray-400 text-xs mb-3">Er is al een bestand geüpload — upload een nieuwe om te vervangen</p>
                    @endif
                    <input
                        type="file"
                        name="game_file"
                        accept=".zip"
                        class="block w-full text-sm text-gray-300
                        file:mr-4 file:py-3 file:px-4
                        file:rounded-xl file:border-0
                        file:bg-green-600 file:text-white
                        file:font-medium
                        hover:file:bg-green-700 cursor-pointer">
                    @error('game_file')
                        <p class="text-red-400 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('admin.games.index') }}"
                       class="px-6 py-3 rounded-xl bg-gray-700 text-white hover:bg-gray-600 transition">
                        Annuleren
                    </a>
                    <button
                        type="submit"
                        class="px-6 py-3 cursor-pointer rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg transition transform hover:scale-105">
                        Game opslaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-admin.layout>