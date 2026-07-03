<x-guest-layout>

<button class="icon-button theme-toggle auth-theme-toggle"
        type="button"
        data-theme-toggle
        aria-label="Switch color theme"
        title="Switch color theme">
    <i class="bi bi-moon-stars" data-theme-icon aria-hidden="true"></i>
</button>

<main class="auth-page">

    <section class="auth-card">

        <a class="auth-brand" href="{{ url('/') }}">
            <span class="brand-icon">
                <i class="bi bi-grid-1x2-fill"></i>
            </span>

            <span>
                <strong>{{ config('app.name') }}</strong>
                <small>Sign in to your admin workspace.</small>
            </span>
        </a>

        <div class="auth-visual">
            <img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                 alt="adminHMD dashboard interface">
        </div>

        @if(session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST"
              action="{{ route('login') }}"
              class="needs-validation">

            @csrf

            <div class="mb-4">

                <p class="eyebrow mb-1">
                    Secure Access
                </p>

                <h1 class="h3 mb-1">
                    Login
                </h1>

                <p class="text-muted mb-0">
                    Sign in to your admin workspace.
                </p>

            </div>

            {{-- Email --}}
            <div class="mb-3">

                <label class="form-label" for="email">
                    Email Address
                </label>

                <input
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username">

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Password --}}
            <div class="mb-3">

                <div class="d-flex justify-content-between">

                    <label class="form-label" for="password">
                        Password
                    </label>

                    @if (Route::has('password.request'))
                        <a class="small fw-semibold"
                           href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif

                </div>

                <input
                    class="form-control @error('password') is-invalid @enderror"
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password">

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Remember --}}
            <div class="form-check mb-4">

                <input class="form-check-input"
                       type="checkbox"
                       id="rememberMe"
                       name="remember">

                <label class="form-check-label"
                       for="rememberMe">
                    Remember me
                </label>

            </div>

            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In
            </button>

        </form>

        @if(Route::has('register'))
            <div class="auth-footer">
                New here?
                <a href="{{ route('register') }}">
                    Create an account
                </a>
            </div>
        @endif

    </section>

</main>

</x-guest-layout>