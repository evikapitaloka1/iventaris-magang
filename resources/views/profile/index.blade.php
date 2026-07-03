@extends('layouts.app')

@section('content')
<main class="dashboard-content bg-light pb-5 min-vh-100">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- Page Heading -->
        <div class="d-flex align-items-center mb-4">
            <div class="bg-primary text-white rounded-3 p-3 me-3 shadow-sm d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="bi bi-person-badge fs-4"></i>
            </div>
            <div>
                <p class="text-uppercase text-primary fw-bold mb-0" style="font-size: 0.75rem; letter-spacing: 1px;">Account</p>
                <h1 class="h4 mb-0 fw-bold text-dark">Profile Management</h1>
                <p class="text-muted small mb-0">Manage your personal information and security.</p>
            </div>
        </div>

        <!-- Success Alert (Feedback User) -->
        @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="row g-4">

            <!-- Profile Card (Kiri) -->
            <div class="col-12 col-lg-4">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="card-body text-center px-4 py-5">
                        
                        <!-- Avatar -->
                        <!-- Avatar -->
                        <div class="position-relative d-inline-block mb-4">
                            @php
                                $user = auth()->user();
                                
                                // Jika user memiliki avatar di database
                                if ($user->avatar) {
                                    $avatarPath = asset('storage/avatars/' . $user->avatar);
                                } else {
                                    // Fallback: Generate avatar dari inisial nama jika belum ada foto
                                    $avatarPath = 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=ffffff&size=130&bold=true';
                                }
                            @endphp
                            
                            <img src="{{ $avatarPath }}" 
                                 class="rounded-circle shadow-sm object-fit-cover" 
                                 style="width: 130px; height: 130px; border: 4px solid #f8f9fa;" 
                                 alt="Profile Photo of {{ auth()->user()->name }}"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0d6efd&color=ffffff&size=130&bold=true';">
                        </div>

                        <hr class="text-muted opacity-25">

                        <!-- Info List -->
                        <ul class="list-group list-group-flush text-start mt-3">
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 border-bottom bg-transparent pb-3">
                                <span class="text-muted small fw-medium"><i class="bi bi-shield-check me-2 text-primary"></i>Role Level</span>
                                <!-- Perbaikan di sini -->
                                <strong class="small">{{ auth()->user()->role?->name ?? '-' }}</strong>
                            </li>
                            <li class="list-group-item px-0 d-flex justify-content-between align-items-center border-0 bg-transparent pt-3">
                                <span class="text-muted small fw-medium"><i class="bi bi-calendar-event me-2 text-primary"></i>Member Since</span>
                                <strong class="small">{{ auth()->user()->created_at->format('d M Y') }}</strong>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Forms Container (Kanan) -->
            <div class="col-12 col-lg-8">

                <!-- Update Profile Form -->
                <div class="card shadow-sm border-0 mb-4 rounded-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h4 class="h6 mb-0 fw-bold text-dark d-flex align-items-center">
                            <div class="bg-primary-subtle text-primary rounded-circle p-2 me-2 d-flex">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            Profile Settings
                        </h4>
                        <p class="text-muted small mb-0 mt-2 ms-5">Update your account information and profile picture.</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <div class="row g-4">
                                <!-- Input Upload Gambar Avatar -->
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Profile Picture</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/jpeg, image/png, image/jpg">
                                        <label class="input-group-text bg-light text-muted"><i class="bi bi-image"></i></label>
                                    </div>
                                    <div class="form-text small mt-2">Format yang diizinkan: JPG, JPEG, PNG. Maksimal 2MB.</div>
                                    @error('avatar')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Input Nama -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Full Name</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                                        <input type="text" name="name" class="form-control border-start-0 ps-0 @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Input Email -->
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                                    <div class="input-group shadow-sm rounded-3">
                                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                                        <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email) }}" required>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 shadow-sm">
                                    <i class="bi bi-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update Password Form -->
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-transparent border-bottom p-4">
                        <h4 class="h6 mb-0 fw-bold text-dark d-flex align-items-center">
                            <div class="bg-success-subtle text-success rounded-circle p-2 me-2 d-flex">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            Security & Password
                        </h4>
                        <p class="text-muted small mb-0 mt-2 ms-5">Ensure your account is using a long, random password to stay secure.</p>
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-12">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Current Password</label>
                                    <input type="password" name="current_password" class="form-control shadow-sm rounded-3 @error('current_password', 'updatePassword') is-invalid @enderror" placeholder="Enter current password">
                                    @error('current_password', 'updatePassword')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">New Password</label>
                                    <input type="password" name="password" class="form-control shadow-sm rounded-3 @error('password', 'updatePassword') is-invalid @enderror" placeholder="Create new password">
                                    @error('password', 'updatePassword')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control shadow-sm rounded-3" placeholder="Repeat new password">
                                </div>
                            </div>

                            <div class="text-end mt-4 pt-3 border-top">
                                <button type="submit" class="btn btn-dark px-4 py-2 rounded-3 shadow-sm">
                                    <i class="bi bi-check2-circle me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</main>
@endsection