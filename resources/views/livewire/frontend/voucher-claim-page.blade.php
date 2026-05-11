<div>
    <div class="container px-4 py-8 mx-auto md:px-6 md:py-12 max-w-7xl">
        <div class="text-center mb-10">
            <h1 class="text-3xl font-bold text-gray-900 md:text-4xl">Voucher & Promo</h1>
            <p class="mt-3 text-gray-600">Klaim voucher di bawah ini dan nikmati diskon spesial saat checkout!</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($vouchers as $v)
                <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-lg group">
                    <!-- Ticket Notch Left -->
                    <div class="absolute left-0 w-6 h-6 -ml-3 bg-gray-50 rounded-full top-1/2 transform -translate-y-1/2 border-r border-gray-200 z-10"></div>
                    <!-- Ticket Notch Right -->
                    <div class="absolute right-0 w-6 h-6 -mr-3 bg-gray-50 rounded-full top-1/2 transform -translate-y-1/2 border-l border-gray-200 z-10"></div>
                    
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center justify-center w-12 h-12 text-pink-600 bg-pink-100 rounded-xl">
                                <i class="fas fa-ticket-alt text-xl"></i>
                            </div>
                            <span class="px-3 py-1 text-xs font-bold text-indigo-700 bg-indigo-100 rounded-full">
                                {{ $v->code }}
                            </span>
                        </div>
                        
                        <h3 class="mb-2 text-2xl font-bold text-gray-900">
                            @if($v->type == 'nominal')
                                Potongan Rp {{ number_format($v->value, 0, ',', '.') }}
                            @else
                                Diskon {{ $v->value }}%
                            @endif
                        </h3>
                        
                        <ul class="mb-6 space-y-2 text-sm text-gray-600">
                            <li class="flex items-center gap-2">
                                <i class="text-green-500 fas fa-check-circle"></i>
                                Min. Belanja: {{ $v->min_purchase ? 'Rp ' . number_format($v->min_purchase, 0, ',', '.') : 'Tanpa Minimal' }}
                            </li>
                            @if($v->type == 'percentage' && $v->max_discount)
                            <li class="flex items-center gap-2">
                                <i class="text-green-500 fas fa-check-circle"></i>
                                Maks. Diskon: Rp {{ number_format($v->max_discount, 0, ',', '.') }}
                            </li>
                            @endif
                            <li class="flex items-center gap-2">
                                <i class="text-orange-500 fas fa-clock"></i>
                                Expired: {{ $v->valid_until ? \Carbon\Carbon::parse($v->valid_until)->format('d M Y H:i') : 'Selamanya' }}
                            </li>
                        </ul>
                        
                        <!-- Line divider -->
                        <div class="w-full border-t border-dashed border-gray-300 mb-6"></div>

                        @if(in_array($v->id, $usedIds))
                            <button disabled class="w-full py-3 font-semibold text-center text-gray-500 bg-gray-100 rounded-xl cursor-not-allowed">
                                <i class="mr-2 fas fa-check-double"></i> Sudah Dipakai
                            </button>
                        @elseif(in_array($v->id, $claimedIds))
                            <button disabled class="w-full py-3 font-semibold text-center text-green-700 bg-green-100 rounded-xl cursor-not-allowed border border-green-300">
                                <i class="mr-2 fas fa-check"></i> Sudah Diklaim
                            </button>
                        @else
                            <button wire:click="claim({{ $v->id }})" class="w-full py-3 font-bold text-center text-white transition-all transform bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl hover:from-purple-700 hover:to-indigo-700 active:scale-95 shadow-md">
                                Klaim Sekarang
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-gray-50 rounded-2xl border border-gray-200">
                    <i class="fas fa-sad-tear text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-medium text-gray-600">Belum ada promo saat ini</h3>
                    <p class="text-gray-500 mt-2">Nantikan voucher menarik dari kami selanjutnya!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
