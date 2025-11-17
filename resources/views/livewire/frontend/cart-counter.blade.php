<a href="{{ route('cart') }}" wire:navigate
    class="nav-link text-gray-600 hover:text-indigo-600 relative pb-1 transition duration-300 {{ request()->routeIs('cart') ? 'text-indigo-600 font-semibold after:absolute after:left-0 after:bottom-0 after:w-full after:h-[2px] after:bg-indigo-600 after:rounded-full' : '' }}">

    <i class="fas fa-shopping-cart fa-lg"></i>

    {{-- Badge ini hanya muncul jika ada item di keranjang --}}
    @if($count > 0)
        <span
            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-3">
            {{ $count }}
        </span>
    @endif
</a>
