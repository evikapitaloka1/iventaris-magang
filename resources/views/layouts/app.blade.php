<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
</head>

<body>

<div class="admin-shell">

    @include('layouts.navigation')

    <div class="admin-main">

        @yield('content')

    </div>

</div>

<script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/assets/js/main.js') }}"></script>

</body>
</html>