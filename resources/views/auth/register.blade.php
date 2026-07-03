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
                <small>Create your admin account.</small>
            </span>
        </a>

        <div class="auth-visual">
            <img src="{{ asset('template/assets/images/png/dasher-ui-bootstrap-5.jpg') }}"
                 alt="adminHMD dashboard interface">
        </div>

        <form method="POST"
              action="{{ route('register') }}"
              class="needs-validation">

            @csrf

            <div class="mb-4">
                <p class="eyebrow mb-1">
                    Secure Access
                </p>

                <h1 class="h3 mb-1">
                    Register
                </h1>

                <p class="text-muted mb-0">
                    Create your admin account.
                </p>
            </div>

            {{-- Name --}}
            <div class="mb-3">

                <label class="form-label" for="name">
                    Full Name
                </label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    required
                    autofocus>

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Email --}}
            <div class="mb-3">

                <label class="form-label" for="email">
                    Email Address
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    required>

                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Password --}}
            <div class="mb-3">

                <label class="form-label" for="password">
                    Password
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    required>

                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Confirm Password --}}
            <div class="mb-3">

                <label class="form-label" for="password_confirmation">
                    Confirm Password
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-control"
                    required>

            </div>

            {{-- Terms --}}
            <div class="form-check mb-4">

                <input
                    class="form-check-input"
                    type="checkbox"
                    id="terms"
                    required>

                <label class="form-check-label" for="terms">
                    I agree to the terms and conditions
                </label>

            </div>

            <button class="btn btn-primary w-100" type="submit">
                <i class="bi bi-person-plus"></i>
                Create Account
            </button>

        </form>

        <div class="auth-footer">
            Already have an account?
            <a href="{{ route('login') }}">
                Sign In
            </a>
        </div>

    </section>

</main>

</x-guest-layout>