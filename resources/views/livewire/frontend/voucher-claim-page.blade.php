<div>
    <div class="container px-4 py-8 mx-auto md:px-6 md:py-12 max-w-7xl min-h-screen bg-[#fcfcfc] md:bg-[#f8f9fa]">
        
        <div class="text-center mb-10 mt-4 md:mt-0">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-[#eedcd3] rounded-full shadow-sm mb-4">
                <i class="text-3xl text-[#5c4033] fas fa-ticket-alt"></i>
            </div>
            <h1 class="text-2xl font-bold text-[#4a3328] md:text-4xl">Voucher & Promo</h1>
            <p class="mt-2 text-xs md:text-sm text-gray-500">Klaim voucher di bawah ini dan nikmati diskon spesial saat checkout!</p>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 pb-24">
            @forelse($vouchers as $v)
                <div class="relative overflow-hidden transition-all duration-300 bg-white border border-gray-100 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.04)] hover:shadow-lg hover:-translate-y-1 group">
                    
                    <div class="absolute left-0 w-8 h-8 -ml-4 bg-[#fcfcfc] md:bg-[#f8f9fa] rounded-full top-[60%] transform -translate-y-1/2 border-r border-gray-100 z-10 shadow-inner"></div>
                    <div class="absolute right-0 w-8 h-8 -mr-4 bg-[#fcfcfc] md:bg-[#f8f9fa] rounded-full top-[60%] transform -translate-y-1/2 border-l border-gray-100 z-10 shadow-inner"></div>
                    
                    <div class="p-6 md:p-8">
                        <div class="flex items-start justify-between mb-5">
                            <div class="flex items-center justify-center w-12 h-12 text-amber-600 bg-amber-50 rounded-2xl border border-amber-100/50">
                                <i class="fas fa-percentage text-xl"></i>
                            </div>
                            <span class="px-4 py-1.5 text-xs font-bold text-[#5c4033] bg-[#eedcd3] border border-[#d2bba6] rounded-full shadow-sm uppercase tracking-widest">
                                {{ $v->code }}
                            </span>
                        </div>
                        
                        <h3 class="mb-3 text-2xl md:text-3xl font-bold text-[#4a3328] leading-tight">
                            @if($v->type == 'nominal')
                                Potongan Rp {{ number_format($v->value, 0, ',', '.') }}
                            @else
                                Diskon {{ $v->value }}%
                            @endif
                        </h3>
                        
                        <ul class="mb-8 space-y-2.5 text-xs md:text-sm text-gray-600">
                            <li class="flex items-center gap-3">
                                <i class="text-[#1c6b38] fas fa-check-circle text-sm"></i>
                                <span>Min. Belanja: <span class="font-bold text-gray-800">{{ $v->min_purchase ? 'Rp ' . number_format($v->min_purchase, 0, ',', '.') : 'Tanpa Minimal' }}</span></span>
                            </li>
                            @if($v->type == 'percentage' && $v->max_discount)
                            <li class="flex items-center gap-3">
                                <i class="text-[#1c6b38] fas fa-check-circle text-sm"></i>
                                <span>Maks. Diskon: <span class="font-bold text-gray-800">Rp {{ number_format($v->max_discount, 0, ',', '.') }}</span></span>
                            </li>
                            @endif
                            <li class="flex items-center gap-3">
                                <i class="text-amber-500 fas fa-clock text-sm"></i>
                                <span>Berlaku s/d: <span class="font-bold text-gray-800">{{ $v->valid_until ? \Carbon\Carbon::parse($v->valid_until)->format('d M Y') : 'Selamanya' }}</span></span>
                            </li>
                        </ul>
                        
                        <div class="relative w-full mb-6">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-dashed border-gray-200"></div>
                            </div>
                        </div>

                        <div class="relative z-20">
                            @if(in_array($v->id, $usedIds))
                                <button disabled class="w-full py-3.5 font-bold text-center text-gray-400 bg-gray-100 rounded-xl cursor-not-allowed">
                                    <i class="mr-2 fas fa-check-double"></i> Sudah Dipakai
                                </button>
                            @elseif(in_array($v->id, $claimedIds))
                                <button disabled class="w-full py-3.5 font-bold text-center text-[#1c6b38] bg-green-50 border border-green-200 rounded-xl cursor-not-allowed">
                                    <i class="mr-2 fas fa-check"></i> Sudah Diklaim
                                </button>
                            @else
                                <button wire:click="claim({{ $v->id }})" class="w-full py-3.5 font-bold text-center text-white transition-all transform bg-[#5c4033] hover:bg-[#4a3328] rounded-xl active:scale-95 shadow-md shadow-amber-900/10">
                                    Klaim Voucher
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white rounded-3xl border border-gray-100 shadow-sm mt-4">
                    <div class="w-24 h-24 mx-auto bg-[#eedcd3] rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-ticket-alt text-4xl text-[#5c4033]"></i>
                    </div>
                    <h3 class="text-lg font-bold text-[#4a3328] mb-1">Belum Ada Promo Saat Ini</h3>
                    <p class="text-sm text-gray-500">Nantikan voucher dan diskon menarik dari Hana's Cake selanjutnya!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>