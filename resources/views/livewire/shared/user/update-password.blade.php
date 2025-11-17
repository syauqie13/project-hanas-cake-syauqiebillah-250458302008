<div>
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Ganti Password</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                    <div class="breadcrumb-item"><a href="#">Profil</a></div>
                    <div class="breadcrumb-item">Ganti Password</div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    {{-- Pindahkan ke tengah, buat lebih ramping --}}
                    <div class="mx-auto col-12 col-md-6 col-lg-5">
                        <div class="card">
                            <form wire:submit.prevent="updatePassword">
                                <div class="card-header">
                                    <h4><i class="mr-2 fas fa-lock text-warning"></i> Ganti Password</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Password Saat Ini</label>
                                        <input type="password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            wire:model="current_password" autocomplete="current-password">
                                        @error('current_password') <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Password Baru</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            wire:model="password" autocomplete="new-password">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        <small class="form-text text-muted">Minimal 8 karakter.</small>
                                    </div>
                                    <div class="form-group">
                                        <label>Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control" wire:model="password_confirmation"
                                            autocomplete="new-password">
                                    </div>
                                </div>
                                <div class="text-right card-footer">
                                    <button type="submit" class="btn btn-warning" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="updatePassword">
                                            Update Password
                                        </span>
                                        <span wire:loading wire:target="updatePassword">
                                            <i class="fas fa-spinner fa-spin"></i> Memproses...
                                        </span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('js')
        {{-- --- PERBAIKAN LOGIKA JAVASCRIPT --- --}}
        <script>
            // Kita gunakan 'livewire:init' agar listener terdaftar dengan aman
            document.addEventListener('livewire:init', () => {

                window.addEventListener('notify', event => {
                    // Ambil icon, default-nya 'success' jika tidak dikirim
                    let icon = event.detail.icon || 'success';

                    // Tentukan judul berdasarkan icon
                    let title;
                    if (icon === 'success') {
                        title = 'Berhasil!';
                    } else if (icon === 'info') {
                        title = 'Info';
                    } else if (icon === 'warning') {
                        title = 'Peringatan';
                    } else {
                        title = 'Gagal!'; // Default untuk 'error'
                    }

                    Swal.fire({
                        icon: icon,
                        title: title,
                        text: event.detail.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                });

            });
        </script>
    @endpush


</div>
