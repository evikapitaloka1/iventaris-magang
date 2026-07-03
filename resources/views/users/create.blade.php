@extends('layouts.app')

@section('content')

<main class="dashboard-content">

    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-person-plus"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Management</p>

                    <h1 class="h3 mb-1">
                        Add User
                    </h1>

                    <p class="text-muted mb-0">
                        Create a new user account.
                    </p>
                </div>
            </div>

            <div class="heading-actions">

                <a href="{{ route('users.index') }}"
                   class="btn btn-outline-secondary btn-sm">

                    <i class="bi bi-arrow-left"></i>

                    Back

                </a>

            </div>

        </div>

        <section class="row g-3">

            <div class="col-lg-8">

                <form action="{{ route('users.store') }}"
                      method="POST"
                      class="panel">

                    @csrf

                    <div class="panel-header">

                        <div>

                            <h2 class="h5 mb-1 section-title">

                                <i class="bi bi-person-plus"></i>

                                User Information

                            </h2>

                            <p class="text-muted mb-0">

                                Fill in all required information.

                            </p>

                        </div>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">

                                Name

                            </label>

                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Email

                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Password

                            </label>

                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Confirm Password

                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Role

                            </label>

                            <select
                                class="form-select"
                                name="role">

                                <option value="">
                                    Choose Role
                                </option>

                                <option value="Admin">
                                    Admin
                                </option>

                                <option value="Manager">
                                    Manager
                                </option>

                                <option value="Staff">
                                    Staff
                                </option>

                            </select>

                        </div>

                    </div>

                    <div class="mt-4 text-end">

                        <a href="{{ route('users.index') }}"
                           class="btn btn-outline-secondary">

                            Cancel

                        </a>

                        <button
                            class="btn btn-primary"
                            type="submit">

                            <i class="bi bi-person-check"></i>

                            Save User

                        </button>

                    </div>

                </form>

            </div>

            <div class="col-lg-4">

                <div class="panel h-100">

                    <div class="panel-header">

                        <h2 class="h5 mb-0">

                            Information

                        </h2>

                    </div>

                    <div class="panel-body">

                        <ul class="list-group list-group-flush">

                            <li class="list-group-item">

                                ✔ Email must be unique.

                            </li>

                            <li class="list-group-item">

                                ✔ Password minimum 8 characters.

                            </li>

                            <li class="list-group-item">

                                ✔ Assign role after user creation.

                            </li>

                        </ul>

                    </div>

                </div>

            </div>

        </section>

    </div>

</main>

@endsection