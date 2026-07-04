<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Inventory Management System">

    <title>{{ config('app.name', 'Inventory App') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('images/logo_inlife.jpg') }}">

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