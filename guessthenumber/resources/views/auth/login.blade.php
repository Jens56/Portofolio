<x-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-8">Log in</h1>
            
            <form method="POST" action="/login" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Email</label>
                    <input 
                        type="email" 
                        name="email"
                        placeholder="Mail"
                        value="{{ old('email') }}"
                        class="w-96 border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                        autocomplete="email"
                        autofocus
                        required>
                    @error('email')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password"
                        placeholder="••••••••"
                        class="w-96 border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                        autocomplete="current-password"
                        required>
                    @error('password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div>
                    <label class="flex items-center text-sm">
                        <input 
                            type="checkbox" 
                            name="remember"
                            class="mr-2">
                        <span class="text-gray-900">Remember me</span>
                    </label>
                </div>

                <button 
                    type="submit"
                    class="w-96 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg mt-8">
                    Sign In
                </button>
            </form>

            <!-- Sign Up Link -->
            <div class="text-center mt-4">
                <p class="text-gray-600">Don't have an account? <a href="/register" class="text-blue-600 hover:text-blue-700 font-semibold">Register</a></p>
            </div>
        </div>
    </div>
</x-layout>
