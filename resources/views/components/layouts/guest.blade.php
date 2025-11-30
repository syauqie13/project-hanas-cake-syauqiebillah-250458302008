<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Hana's Cake</title>

    <!-- SweetAlert2 (Wajib ada untuk notifikasi) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- General CSS Files (Bawaan Stisla) -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('icon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('icon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('icon/site.webmanifest') }}" />

    <!-- CSS Libraries (Bawaan Stisla) -->
    <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-social/bootstrap-social.css') }}">

    <!-- Template CSS (Bawaan Stisla) -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    @livewireStyles
</head>

<body>
    <div id="app">
        {{--
        Ini adalah wrapper untuk halaman auth Stisla.
        $slot (konten Anda) akan dimasukkan ke sini.
        --}}
        {{ $slot }}
    </div>

    <!-- General JS Scripts (Bawaan Stisla) -->
    <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/modules/popper.js') }}"></script>
    <script src="{{ asset('assets/modules/tooltip.js') }}"></script>
    <script src="{{ asset('assets/modules/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/modules/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/modules/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/stisla.js') }}"></script>

    <!-- Template JS File -->
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    @livewireScripts
    @stack('js') {{-- Stack ini untuk notifikasi SweetAlert --}}
</body>

</html>
