<div>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid navbar-inner" style="max-width: 1400px;">
            <a class="navbar-brand" href="#">
                <i class="fas fa-cash-register"></i>
                <span>POS System</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Kasir</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-history"></i>
                            <span>Riwayat</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a href="{{ route('karyawan.dashboard') }}" class="btn-dashboard text-decoration-none">
                            <i class="fas fa-th-large"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <div class="user-profile">
                            <div class="user-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <span class="user-name d-none d-lg-block">Karyawan</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
