<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Point of Sale - Kasir Modern</title>

    <!-- Tambahkan ini SEBELUM script lain yang pakai $ -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.min.css" rel="stylesheet">

    <link rel="icon" type="image/png" href="{{ asset('icon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('icon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('icon/site.webmanifest') }}" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Midtrans Snap -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('services.midtrans.client_key') }}"></script>

    @stack('css')

</head>

<body>
    <!-- Navbar -->
    <livewire:atom.pos.navbar />

    <!-- Main Content -->
    {{ $slot }}

    @stack('js')
    @livewireScripts

    <iframe id="receipt-printer" style="position: absolute; left: -9999px; top: -9999px; width: 302px; height: 100px; border: 0;"></iframe>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.3/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
