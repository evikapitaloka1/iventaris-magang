<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Inventory Management System">

    <title>{{ config('app.name', 'Inventory App') }}</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180"
        href="{{ asset('template/assets/images/favicon_io/apple-touch-icon.png') }}">

    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('template/assets/images/favicon_io/favicon-32x32.png') }}">

    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('template/assets/images/favicon_io/favicon-16x16.png') }}">

    <link rel="manifest"
        href="{{ asset('template/assets/images/favicon_io/site.webmanifest') }}">

    <!-- CSS -->
    <link rel="stylesheet"
        href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">

    <link rel="stylesheet"
        href="{{ asset('template/assets/css/style.css') }}">

</head>

<body class="auth-body">

    {{ $slot }}

    <!-- Bootstrap JS -->
    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('template/assets/js/main.js') }}"></script>

</body>

</html>