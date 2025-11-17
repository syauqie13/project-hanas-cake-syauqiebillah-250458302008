<div>
    <div class="navbar-bg" style="background-color: #34395e;"></div>
    <nav class="navbar navbar-expand-lg main-navbar">

        <form class="mr-auto form-inline" wire:submit.prevent="performSearch">
            <ul class="mr-3 navbar-nav">
                <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
                <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                            class="fas fa-search"></i></a></li>
            </ul>
            <div class="search-element">
                <input class="form-control" type="search" placeholder="Cari produk, order, pelanggan..."
                    aria-label="Search" data-width="250" wire:model.defer="search">

                <button type="submit" style="display: none;"></button>
            </div>
        </form>

        <ul class="navbar-nav navbar-right">

            {{-- --- PERBAIKAN NOTIFIKASI DINAMIS --- --}}
            <li class="dropdown dropdown-list-toggle"><a href="#" data-toggle="dropdown"
                    class="nav-link notification-toggle nav-link-lg {{ $notifCount > 0 ? 'beep' : '' }}">
                    <i class="far fa-bell"></i>
                    {{-- Tampilkan jumlah jika lebih dari 0 --}}
                </a>
                <div class="dropdown-menu dropdown-list dropdown-menu-right">
                    <div class="dropdown-header">Notifikasi ({{ $notifCount }})
                        <div class="float-right">
                            <a href="#">Refresh</a>
                        </div>
                    </div>
                    <div class="dropdown-list-content dropdown-list-icons">

                        {{-- Loop notifikasi dinamis --}}
                        @forelse ($notifications as $notif)
                            <a href="{{ $notif['url'] }}" class="dropdown-item dropdown-item-unread" wire:navigate>
                                <div class="text-white dropdown-item-icon {{ $notif['color'] }}">
                                    <i class="{{ $notif['icon'] }}"></i>
                                </div>
                                <div class="dropdown-item-desc">
                                    {{ $notif['message'] }}
                                </div>
                            </a>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="mb-2 fas fa-check-circle d-block" style="font-size: 30px;"></i>
                                <p>Semua stok aman terkendali.</p>
                            </div>
                        @endforelse

                    </div>
                </div>
            </li>
            {{-- --- AKHIR PERBAIKAN --- --}}


            <li class="dropdown"><a href="#" data-toggle="dropdown"
                    class="nav-link dropdown-toggle nav-link-lg nav-link-user">

                    {{-- PERBAIKAN: Foto Profil Dinamis --}}
                    <img alt="image"
                        src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : asset('assets/img/avatar/avatar-1.png') }}"
                        class="mr-1 rounded-circle" style="object-fit: cover; width: 30px; height: 30px;"> {{-- Ukuran
                    disesuaikan --}}

                    <div class="d-sm-none d-lg-inline-block">
                        {{ optional(auth()->user())->name }}
                    </div>

                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-title"> Bergabung: {{ auth()->user()->created_at->format('d M Y') }}</div>
                    @php
                        $profileRoute = '#';
                        if (auth()->user()->role === 'admin') {
                            $profileRoute = route('admin.profile');
                        } elseif (auth()->user()->role === 'karyawan') {
                            $profileRoute = route('karyawan.profile');
                        }
                    @endphp
                    <a href="{{ $profileRoute }}" class="dropdown-item has-icon">
                        <i class="far fa-user"></i> Profile
                    </a>
                    <a href="#" class="dropdown-item has-icon">
                        <i class="fas fa-bolt"></i> Activities
                    </a>
                    @php
                        $updatePassword = '#';
                        if (auth()->user()->role === 'admin') {
                            $updatePassword = route('admin.update.password');
                        } elseif (auth()->user()->role === 'karyawan') {
                            $updatePassword = route('karyawan.update.password');
                        }
                    @endphp
                    <a href="{{ $updatePassword }}" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <livewire:auth.logout />
                </div>
            </li>
        </ul>
    </nav>
