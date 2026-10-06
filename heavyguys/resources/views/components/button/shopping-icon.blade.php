<div class="relative inline-block">
    <i class="las la-shopping-cart text-2xl"></i>

    @if(session('cart') && count(session('cart')) > 0)
        <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
            {{ count(session('cart')) }}
        </span>
    @endif
</div>