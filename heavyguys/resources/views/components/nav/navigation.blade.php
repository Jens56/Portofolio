<nav class="fixed top-0 left-0 right-0 bg-gray-900 bg-opacity-30 px-6 py-3 flex items-center justify-between z-20">
    <div class="flex items-center">
        <a href="/">
            <img src="{{ asset('images/logoZonderwit.png') }}" alt="Logo" class="h-10 w-10 rounded-full">
        </a>
    </div>

    <div class="hidden md:flex items-center gap-8">
        <a href="/" class="text-white hover:text-yellow-400 font-medium">Home</a>
        <a href="/games" class="text-white hover:text-yellow-400 font-medium">Games</a>
        <a href="/blog" class="text-white hover:text-yellow-400 font-medium">Blog</a>
        <a href="/about" class="text-white hover:text-yellow-400 font-medium">About us</a>
        <a href="/contact" class="text-white hover:text-yellow-400 font-medium">Contact</a>
        @auth
            @if(auth()->user()->is_admin == 1)
                <span class="text-gray-500">|</span>
                <a href="/admin/dashboard" class="text-white hover:text-yellow-400 font-medium">Admin panel</a>
            @endif
        @endauth
    </div>
    <div class="hidden md:flex items-center gap-3">
        <a href="/shopping-cart" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-5 py-2 rounded-full">
            <x-button.shopping-icon />
        </a>
        @guest
            <a href="/login" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-5 py-2 rounded-full">
                login
            </a>
        @endguest
        @auth
            <x-button.logout />
        @endauth
    </div>
    
    <button id="hamburger" class="md:hidden flex flex-col justify-center items-center gap-1.5 w-10 h-10 focus:outline-none">
        <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
        <span class="block w-6 h-0.5 bg-white transition-all duration-300"></span>
    </button>
</nav>
<div id="mobile-menu" class="fixed top-16 left-0 right-0 bg-gray-900 bg-opacity-95 z-10 hidden flex-col px-6 py-6 gap-4 md:hidden">
    <a href="/" class="text-white hover:text-yellow-400 font-medium text-lg">Home</a>
    <a href="/games" class="text-white hover:text-yellow-400 font-medium text-lg">Games</a>
    <a href="/blog" class="text-white hover:text-yellow-400 font-medium text-lg">Blog</a>
    <a href="/about" class="text-white hover:text-yellow-400 font-medium text-lg">About us</a>
    <a href="/contact" class="text-white hover:text-yellow-400 font-medium text-lg">Contact</a>
    @auth
        @if(auth()->user()->is_admin == 1)
            <a href="/admin/dashboard" class="text-white hover:text-yellow-400 font-medium text-lg">Admin panel</a>
        @endif
    @endauth
    <hr class="border-gray-700">
    <a href="/shopping-cart" class="bg-cyan-400 hover:bg-cyan-500 text-white font-semibold px-5 py-2 rounded-full text-center">
        <x-button.shopping-icon />
    </a>
    @guest
        <a href="/login" class="bg-cyan-400 hover:bg-cyan-500 text-white font-semibold px-5 py-2 rounded-full text-center">
            login
        </a>
    @endguest
    @auth
        <x-button.logout />
    @endauth
</div>

<script>
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobile-menu');

    hamburger.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('flex');
    });
</script>