<a href="{{ route('cart') }}" class="relative text-gray-600 hover:text-indigo-600">
    <i class="fas fa-shopping-cart fa-lg"></i>

    {{-- Badge ini hanya muncul jika ada item di keranjang --}}
    @if($count > 0)
        <span
            class="absolute flex items-center justify-center w-5 h-5 text-xs text-white bg-red-500 rounded-full -top-2 -right-3">
            {{ $count }}
        </span>
    @endif
</a>
