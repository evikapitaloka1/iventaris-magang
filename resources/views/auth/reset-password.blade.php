<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
</head>

<body class="auth-body">

<button class="icon-button theme-toggle auth-theme-toggle"
        type="button"
        data-theme-toggle>
    <i class="bi bi-moon-stars" data-theme-icon></i>
</button>

<main class="auth-page">

    <section class="auth-card">

        <a class="auth-brand" href="{{ route('login') }}">
            <span class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span>
                <strong>{{ config('app.name') }}</strong>
                <small>Create your new password.</small>
            </span>
        </a>

        <div class="auth-visual">
            <img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                 alt="Reset Password">
        </div>

        <form method="POST"
              action="{{ route('password.store') }}">

            @csrf

            <input type="hidden"
                   name="token"
                   value="{{ $request->route('token') }}">

            <div class="mb-4">

                <p class="eyebrow mb-1">
                    Secure Access
                </p>

                <h1 class="h3 mb-1">
                    Reset Password
                </h1>

                <p class="text-muted mb-0">
                    Create a new password for your account.
                </p>

            </div>

            {{-- Email --}}
            <div class="mb-3">

                <label class="form-label">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $request->email) }}"
                    required
                    autofocus>

                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Password --}}
            <div class="mb-3">

                <label class="form-label">
                    New Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required>

                @error('password')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Confirm Password --}}
            <div class="mb-4">

                <label class="form-label">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required>

            </div>

            <button class="btn btn-primary w-100">

                <i class="bi bi-shield-lock"></i>

                Reset Password

            </button>

        </form>

        <div class="auth-footer mt-3">

            <a href="{{ route('login') }}">
                <i class="bi bi-arrow-left"></i>
                Back to Login
            </a>

        </div>

    </section>

</main>

<script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/assets/js/main.js') }}"></script>

</body>
</html>