<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password | {{ config('app.name') }}</title>

    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/vendors/bootstrap-icons/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">
</head>

<body class="auth-body">

<button class="icon-button theme-toggle auth-theme-toggle"
        type="button"
        data-theme-toggle
        aria-label="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon></i>
</button>

<main class="auth-page">

    <section class="auth-card">

        <a class="auth-brand" href="{{ route('dashboard') }}">
            <span class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span>
                <strong>{{ config('app.name') }}</strong>
                <small>Get a reset link for your account.</small>
            </span>
        </a>

        <div class="auth-visual">
            <img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                 alt="Dashboard">
        </div>

        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('password.email') }}"
              class="needs-validation">

            @csrf

            <div class="mb-4">
                <p class="eyebrow mb-1">
                    Secure Access
                </p>

                <h1 class="h3 mb-1">
                    Forgot Password
                </h1>

                <p class="text-muted mb-0">
                    Enter your email address and we'll send you a password reset link.
                </p>
            </div>

            <div class="mb-4">

                <label for="email" class="form-label">
                    Email Address
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}"
                    required
                    autofocus>

                @error('email')
                    <div class="invalid-feedback d-block">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-envelope-arrow-up"></i>
                Send Reset Link
            </button>

        </form>

        <p class="text-muted small mt-3 mb-0">
            Check your inbox and spam folder after submitting.
        </p>

        <div class="auth-footer">
            Remember your password?
            <a href="{{ route('login') }}">
                Back to Login
            </a>
        </div>

    </section>

</main>

<script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('template/assets/js/main.js') }}"></script>

</body>
</html>