<div>
    <section class="section">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                    <div class="login-brand">
                        {{-- Logo Toko --}}
                        <img src="{{ asset('assets/img/logo-hanas-cake.png') }}" alt="logo" width="100"
                            class="shadow-light rounded-circle">
                    </div>

                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Verifikasi Email Anda</h4>
                        </div>

                        <div class="card-body">
                            <p class="text-muted">
                                Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan
                                mengklik link yang baru saja kami kirimkan ke email Anda.
                            </p>

                            <p class="text-small text-muted">
                                Jika Anda tidak menerima email tersebut, kami dengan senang hati akan mengirimkan yang
                                baru.
                            </p>

                            <div class="form-group">
                                <button wire:click="resendVerification" wire:loading.attr="disabled"
                                    class="btn btn-primary btn-lg btn-block" tabindex="4">
                                    <span wire:loading.remove wire:target="resendVerification">Kirim Ulang Link
                                        Verifikasi</span>
                                    <span wire:loading wire:target="resendVerification"><i
                                            class="fas fa-spinner fa-spin"></i> Mengirim...</span>
                                </button>
                            </div>

                            <div class="mt-4 text-center">
                                <button wire:click="logout" class="btn btn-link text-danger">Logout / Ganti
                                    Akun</button>
                            </div>
                        </div>
                    </div>
                    <div class="simple-footer">
                        Copyright &copy; Hana's Cake 2025
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Script SweetAlert (Wajib ada di layout guest juga) --}}
    @push('js')
        <script>
            window.addEventListener('notify', event => {
                Swal.fire({
                    icon: event.detail.icon,
                    title: 'Notifikasi',
                    text: event.detail.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        </script>
    @endpush
</div>
