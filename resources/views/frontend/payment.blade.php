<x-layouts.ecommerce>
    <div class="max-w-2xl p-8 mx-auto mt-10 text-center bg-white border border-gray-100 shadow-lg rounded-xl">

        <div class="mb-6">
            <div
                class="flex items-center justify-center w-16 h-16 mx-auto mb-4 text-indigo-600 bg-indigo-100 rounded-full">
                <i class="text-2xl fas fa-wallet"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Selesaikan Pembayaran</h1>
            <p class="mt-2 text-gray-500">Order ID: #{{ $order->id }}</p>
            <p class="mt-4 text-3xl font-bold text-indigo-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
        </div>

        <div class="space-y-4">
            <p class="text-sm text-gray-600">
                Silakan selesaikan pembayaran Anda sekarang. Klik tombol di bawah jika popup tidak muncul otomatis.
            </p>

            <button id="pay-button"
                class="w-full px-8 py-3 font-semibold text-white transition duration-200 transform rounded-full shadow-lg md:w-auto bg-gradient-to-r from-indigo-600 to-purple-600 hover:shadow-xl hover:-translate-y-1">
                Bayar Sekarang
            </button>

            <div class="mt-4">
                <a href="{{ route('pelanggan.my-orders') }}"
                    class="text-sm text-gray-500 underline hover:text-gray-700">
                    Kembali ke Pesanan Saya
                </a>
            </div>
        </div>
    </div>

    @push('js')
        <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.client_key') }}"></script>
        <script type="text/javascript">
            const payButton = document.getElementById('pay-button');
            const snapToken = '{{ $snapToken }}';

            function triggerSnap() {
                window.snap.pay(snapToken, {
                    onSuccess: function (result) {
                        // Jika sukses, kembali ke My Orders
                        window.location.href = "{{ route('pelanggan.my-orders') }}";
                    },
                    onPending: function (result) {
                        // Jika pending, kembali ke My Orders
                        window.location.href = "{{ route('pelanggan.my-orders') }}";
                    },
                    onError: function (result) {
                        alert("Pembayaran gagal!");
                    },
                    onClose: function () {
                        console.log('Customer closed the popup without finishing the payment');
                    }
                });
            }

            // Trigger otomatis saat halaman dimuat
            triggerSnap();

            // Trigger manual jika tombol diklik
            payButton.addEventListener('click', function () {
                triggerSnap();
            });
        </script>
    @endpush
</x-layouts.ecommerce>
