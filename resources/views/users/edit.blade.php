@extends('layouts.app')

@section('content')

<main class="dashboard-content">
<div class="container-fluid px-3 px-lg-4 py-4">

<div class="page-heading">

    <div class="page-heading-copy">

        <span class="page-icon">
            <i class="bi bi-pencil-square"></i>
        </span>

        <div>

            <p class="eyebrow mb-1">Management</p>

            <h1 class="h3 mb-1">
                Edit User
            </h1>

            <p class="text-muted mb-0">
                Update user information.
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

<form action="{{ route('users.update', $user->id) }}" method="POST" class="panel mt-3">

    @csrf
    @method('PUT')

    <div class="panel-header">

        <h2 class="h5 mb-0">
            User Information
        </h2>

    </div>

    <div class="p-4">

        <div class="row g-3">

            <div class="col-md-6">

                <label class="form-label">
                    Name
                </label>

                <input type="text"
                       name="name"
                       class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name', $user->name) }}">

                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Email
                </label>

                <input type="email"
                       name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $user->email) }}">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Role
                </label>

                <select name="role_id"
                        class="form-select @error('role_id') is-invalid @enderror">

                    <option value="">-- Pilih Role --</option>

                    @foreach($roles as $role)
                        <option value="{{ $role->id }}"
                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach

                </select>

                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

            <div class="col-md-6">

                <label class="form-label">
                    Password <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small>
                </label>

                <input type="password"
                       name="password"
                       class="form-control @error('password') is-invalid @enderror">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

        </div>

        <div class="mt-4 text-end">

            <a href="{{ route('users.index') }}"
               class="btn btn-secondary">

                Cancel

            </a>

            <button class="btn btn-primary">

                <i class="bi bi-check-circle"></i>

                Update User

            </button>

        </div>

    </div>

</form>

</div>
</main>

@endsection