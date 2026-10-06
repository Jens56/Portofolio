@auth
    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
        @csrf
        <button type="submit" style="background:none; border:none; cursor:pointer; padding:0; color:inherit;">
            <i class="las la-sign-out-alt"></i>
        </button>
    </form>
@endauth
