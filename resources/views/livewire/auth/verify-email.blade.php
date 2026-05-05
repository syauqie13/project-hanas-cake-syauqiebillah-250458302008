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
                                memasukkan 6 digit kode OTP yang baru saja kami kirimkan ke email Anda.
                            </p>

                            <form wire:submit="verifyCode">
                                <div class="form-group">
                                    <label for="verificationCode">Kode Verifikasi</label>
                                    <input id="verificationCode" type="text" class="form-control" name="verificationCode" tabindex="1" required autofocus wire:model="verificationCode" placeholder="Masukkan 6 digit kode">
                                    @error('verificationCode')
                                        <div class="text-danger mt-2 text-sm">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="2" wire:loading.attr="disabled">
                                        <span wire:loading.remove wire:target="verifyCode">Verifikasi Email</span>
                                        <span wire:loading wire:target="verifyCode"><i class="fas fa-spinner fa-spin"></i> Memverifikasi...</span>
                                    </button>
                                </div>
                            </form>

                            <div class="mt-4 text-center">
                                <p class="text-small text-muted">
                                    Tidak menerima kode?
                                </p>
                                <button wire:click="resendVerification" wire:loading.attr="disabled"
                                    class="btn btn-outline-primary btn-sm btn-block" tabindex="3">
                                    <span wire:loading.remove wire:target="resendVerification">Kirim Ulang Kode</span>
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
