<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>General Dashboard &mdash; Stisla</title>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/weather-icon/css/weather-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/weather-icon/css/weather-icons-wind.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/summernote/summernote-bs4.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    <!-- Start GA -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-94034622-3"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
    </style>


    @livewireStyles
    <!-- /END GA -->
    @stack('styles')

</head>

<body>



    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <livewire:atom.navbar />
            <livewire:atom.sidebar />

            {{ $slot }}

            <livewire:atom.footer />
        </div>
    </div>


    <!-- General JS Scripts -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('assets/modules/simple-weather/jquery.simpleWeather.min.js') }}"></script>
    <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>
    <script src="{{ asset('assets/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/modules/chocolat/dist/js/jquery.chocolat.min.js') }}"></script>

    <!-- Page Specific JS File -->

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script> {{-- <- Pemicu fadeOut() ada di sini --}} <script
        src="{{ asset('assets/js/custom.js') }}"></script>

        <script>
            document.addEventListener('livewire:navigated', () => {
                // Re-init Dropdowns
                if ($('[data-toggle="dropdown"]').length) {
                    $('[data-toggle="dropdown"]').dropdown('dispose');
                    $('[data-toggle="dropdown"]').dropdown();
                }

                // Re-init Tooltips
                if ($('[data-toggle="tooltip"]').length) {
                    $('[data-toggle="tooltip"]').tooltip('dispose');
                    $('[data-toggle="tooltip"]').tooltip();
                }

                // Re-init Popovers
                if ($('[data-toggle="popover"]').length) {
                    $('[data-toggle="popover"]').popover('dispose');
                    $('[data-toggle="popover"]').popover();
                }

                // Re-init Custom Scrollbar (Nicescroll)
                if (jQuery().nicescroll) {
                    $(".main-sidebar").getNiceScroll().resize();
                }
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                window.addEventListener('confirm-logout', () => {
                    Swal.fire({
                        title: 'Yakin ingin logout?',
                        text: 'Anda akan keluar dari sesi ini.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Logout',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Livewire.dispatch('execute-logout');
                        }
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // --- 1. Fade out preloader saat halaman pertama selesai load ---
                const preloader = document.querySelector('.preloader');
                if (preloader) {
                    $(preloader).fadeOut(500); // Stisla style fadeOut
                }

                // --- 2. Livewire hook untuk preloader saat navigasi / ajax ---
                if (window.Livewire) {
                    Livewire.hook('message.sent', () => {
                        document.body.classList.add('wire-loading');
                        if (preloader) {
                            $(preloader).fadeIn(200);
                        }
                    });

                    Livewire.hook('message.processed', () => {
                        document.body.classList.remove('wire-loading');
                        if (preloader) {
                            $(preloader).fadeOut(300);
                        }
                    });
                }

                // --- 3. Re-init Bootstrap / Stisla components setelah navigasi Livewire ---
                document.addEventListener('livewire:navigated', () => {
                    // Dropdowns
                    if ($('[data-toggle="dropdown"]').length) {
                        $('[data-toggle="dropdown"]').dropdown('dispose');
                        $('[data-toggle="dropdown"]').dropdown();
                    }

                    // Tooltips
                    if ($('[data-toggle="tooltip"]').length) {
                        $('[data-toggle="tooltip"]').tooltip('dispose');
                        $('[data-toggle="tooltip"]').tooltip();
                    }

                    // Popovers
                    if ($('[data-toggle="popover"]').length) {
                        $('[data-toggle="popover"]').popover('dispose');
                        $('[data-toggle="popover"]').popover();
                    }

                    // Custom scrollbar
                    if (jQuery().nicescroll) {
                        $(".main-sidebar").getNiceScroll().resize();
                    }
                });

            });
        </script>



        @stack('js')
        @livewireScripts
        @livewireScriptConfig

</body>

</html>
