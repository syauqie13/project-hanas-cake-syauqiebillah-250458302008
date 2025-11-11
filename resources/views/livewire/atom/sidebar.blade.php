<div>
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="index.html">Hana's Cake</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="index.html">St</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                @if(Auth::check() && Auth::user()->role === 'admin')
                    <li class="dropdown {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-link"><i
                                class="fas fa-fire"></i><span>Dashboard</span></a>
                    </li>
                    <li class="menu-header">Manajemen</li>
                    <li class="dropdown {{ request()->routeIs('admin.list-karyawan*') ? 'active' : '' }}">
                        <a href="{{ route('admin.list-karyawan') }}" wire:navigate class="nav-link"><i
                                class="fas fa-users"></i><span>Data Karyawan</span></a>
                    </li>
                    <li class="dropdown {{ request()->routeIs('admin.list-product*') ? 'active' : '' }}">
                        <a href="{{ route('admin.list-product') }}" wire:navigate class="nav-link"><i
                                class="fas fa-box"></i><span>Data Product</span></a>
                    </li>
                    <li class="dropdown {{ request()->routeIs('admin.list-category*') ? 'active' : '' }}">
                        <a href="{{ route('admin.list-category') }}" wire:navigate class="nav-link"><i
                                class="fas fa-tags"></i><span>Data Category</span></a>
                    </li>
                @endif

                @if(Auth::check() && Auth::user()->role === 'karyawan')
                    <li class="dropdown {{ request()->routeIs('karyawan.dashboard*') ? 'active' : '' }}">
                        <a href="{{ route('karyawan.dashboard') }}" wire:navigate class="nav-link"><i
                                class="fas fa-fire"></i><span>Dashboard</span></a>
                    </li>
                    <li class="menu-header">Manajemen</li>
                    <li class="dropdown {{ request()->routeIs('karyawan.list-product*') ? 'active' : '' }}">
                        <a href="{{ route('karyawan.list-product') }}" wire:navigate class="nav-link"><i
                                class="fas fa-box"></i><span>Data Product</span></a>
                    </li>
                    <li class="dropdown {{ request()->routeIs('karyawan.list-category*') ? 'active' : '' }}">
                        <a href="{{ route('karyawan.list-category') }}" wire:navigate class="nav-link"><i
                                class="fas fa-tags"></i><span>Data Category</span></a>
                    </li>
                    <li class="dropdown {{ request()->routeIs('karyawan.list-inventory*') ? 'active' : '' }}">
                        <a href="{{ route('karyawan.list-inventory') }}" wire:navigate class="nav-link"><i
                                class="fas fa-boxes"></i><span>Data Inventory</span></a>
                    </li>
                    <li class="menu-header">Kasir</li>
                    <li class="dropdown {{ request()->routeIs('karyawan.pos*') ? 'active' : '' }}">
                        <a href="{{ route('karyawan.pos') }}" class="nav-link">
                            <i class="fas fa-cash-register"></i>
                            <span>Transaksi POS</span>
                        </a>
                    </li>

                @endif
            </ul>

            <div class="p-3 mt-4 mb-4 hide-sidebar-mini">
                <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
                    <i class="fas fa-rocket"></i> Documentation
                </a>
            </div>
        </aside>
    </div>
</div>
