@auth
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold px-5 py-2 rounded-full" style="border:none; cursor:pointer;">
            logout
        </button>
    </form>
@endauth
