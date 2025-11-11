@extends('components.layouts.app') {{-- Sesuaikan dengan layout utama Anda --}}

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Status Pembayaran</h1>
            </div>

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-8 offset-md-2">
                        <div class="shadow-sm card card-primary">
                            <div class="card-body">
                                <div class="py-5 text-center">

                                    {{-- Ikon berdasarkan status --}}
                                    @if($icon == 'success')
                                        <i class="fas fa-check-circle fa-4x text-{{ $color }} mb-4"></i>
                                    @elseif($icon == 'info')
                                        <i class="fas fa-hourglass-half fa-4x text-{{ $color }} mb-4"></i>
                                    @elseif($icon == 'error')
                                        <i class="fas fa-times-circle fa-4x text-{{ $color }} mb-4"></i>
                                    @else
                                        <i class="fas fa-exclamation-triangle fa-4x text-{{ $color }} mb-4"></i>
                                    @endif

                                    {{-- Judul dan Pesan dari Controller --}}
                                    <h2 class="h3 font-weight-bold text-{{ $color }}">{{ $title }}</h2>
                                    <p class="mt-3 text-muted h6">
                                        {{ $message }}
                                    </p>

                                    <hr>

                                    {{-- Tombol Kembali ke POS --}}
                                    <a href="{{ route('karyawan.pos') }}" class="mt-4 shadow-sm btn btn-primary btn-lg">
                                        <i class="mr-2 fas fa-cash-register"></i>
                                        Kembali ke Kasir (POS)
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
