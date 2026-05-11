<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'Login')</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" type="image/png" href="{{ asset('icon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('icon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('icon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('icon/site.webmanifest') }}" />

    @stack('css')
    @livewireStyles
</head>

<body class="bg-gray-50 text-gray-900 antialiased font-sans">

    <div id="auth" class="min-h-screen flex flex-col justify-center sm:px-6 lg:px-8">
        {{ $slot }}
    </div>

    @livewireScripts
    @stack('js')

</body>

</html>