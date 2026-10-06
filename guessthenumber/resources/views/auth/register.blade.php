<x-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md">
            <h1 class="text-3xl font-bold text-center mb-8">Create Account</h1>
            
            <form method="POST" action="/register" class="space-y-6">
                @csrf
                
                <!-- Name Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Name</label>
                    <input 
                        type="text" 
                        name="name"
                        placeholder="Name"
                        value="{{ old('name') }}"
                        class="w-96 border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                        required
                    >
                    @error('name')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

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
                        required
                    >
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
                        autocomplete="new-password"
                        required
                    >
                    @error('password')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Confirm Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation"
                        placeholder="••••••••"
                        class="w-96 border-2 border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-blue-500"
                        autocomplete="new-password"
                        required
                    >
                </div>

                <!-- Register Button -->
                <button 
                    type="submit"
                    class="w-96 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 rounded-lg mt-8"
                >
                    Register
                </button>
            </form>

            <!-- Sign In Link -->
            <div class="text-center mt-4">
                <p class="text-gray-600">Already have an account? <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold">Sign in</a></p>
            </div>
        </div>
    </div>
</x-layout>
