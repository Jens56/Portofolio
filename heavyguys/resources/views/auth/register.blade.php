<x-layout>
    <div class="min-h-screen bg-gradient-to-b from-gray-900 to-black flex items-center justify-center px-5">
        <div class="w-full max-w-md bg-gradient-to-br from-gray-800 to-gray-900 p-6 md:p-8 rounded-xl border border-gray-700">
            <h1 class="text-3xl md:text-4xl font-bold text-center text-white mb-8">Account aanmaken</h1>
            <form method="POST" action="/register" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-white text-sm font-bold mb-2">Naam</label>
                    <input type="text" name="name" placeholder="Je naam" value="{{ old('name') }}" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-2">Email</label>
                    <input type="email" name="email" placeholder="je@email.com" value="{{ old('email') }}" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-2">Wachtwoord</label>
                    <input type="password" name="password" placeholder="••••••••" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-white text-sm font-bold mb-2">Wachtwoord bevestigen</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••" class="w-full p-3 rounded-lg bg-gray-700 text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-700 hover:to-blue-600 text-white font-semibold py-3 rounded-lg transition-all duration-300 shadow-lg mt-6">Account aanmaken</button>
            </form>
            <div class="text-center mt-6">
                <p class="text-gray-400 text-sm">Al een account? <a href="/login" class="text-blue-400 hover:text-blue-300 font-semibold">Inloggen</a></p>
            </div>
        </div>
    </div>
</x-layout>

