@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- 1. Page Heading -->
        <div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div class="page-heading-copy d-flex align-items-center gap-3">
                <span class="page-icon">
                    <i class="bi bi-speedometer2 fs-3" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1 text-muted">Overview</p>
                    <h1 class="h3 mb-1">Dashboard</h1>
                    <p class="text-muted mb-0">
                        Welcome back, <strong>{{ auth()->user()->name }}</strong> 👋 | Monitor performance, sales, users, and support.
                    </p>
                </div>
            </div>
            
            <div class="heading-actions">
                <button class="btn btn-outline-secondary btn-sm" type="button">
                    <i class="bi bi-download" aria-hidden="true"></i> Export
                </button>
                {{-- Hanya Admin & Manager yang bisa Create Report --}}
                @if(in_array(auth()->user()->role?->name, ['Admin', 'Manager']))
                <button class="btn btn-primary btn-sm" type="button">
                    <i class="bi bi-file-earmark-plus" aria-hidden="true"></i> Create Report
                </button>
                @endif
            </div>
        </div>

        <!-- 2. Dynamic User Information Panel (Terbuka untuk Semua) -->
        <div class="panel mb-4">
            <div class="panel-header">
                <h2 class="h5 mb-0"><i class="bi bi-person-badge me-2"></i>User Information</h2>
            </div>
            <div class="panel-body p-4">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="180" class="text-muted">Name</th>
                        <td class="fw-medium">{{ auth()->user()->name }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>{{ auth()->user()->email }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Role</th>
                        <td>
                            <span class="badge text-bg-primary">{{ auth()->user()->role?->name ?? 'No Role Assigned' }}</span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- 3. Dashboard Metrics (Hanya untuk Admin dan Manager) --}}
        @if(in_array(auth()->user()->role?->name, ['Admin', 'Manager']))
        <section class="row g-3 mb-4" aria-label="Dashboard metrics">
            <!-- (Kode metric-card tetap sama seperti aslinya, dipersingkat agar fokus ke logika) -->
            <div class="col-12 col-sm-6 col-xl-3">
                <article class="metric-card metric-primary">
                    <div class="metric-top">
                        <span class="metric-label">Revenue</span>
                        <span class="metric-icon"><i class="bi bi-currency-dollar"></i></span>
                    </div>
                    <div class="metric-value">$48,240</div>
                </article>
            </div>
            <!-- ... metric lainnya ... -->
        </section>
        
        <!-- 4. Charts & Team Activity (Hanya untuk Admin dan Manager) -->
        <section class="row g-3 mb-4">
            <!-- (Kode chart & activity tetap sama) -->
        </section>
        @endif

        {{-- 5. Recent Users Table (HANYA UNTUK ADMIN) --}}
        @if(auth()->user()->role?->name === 'Admin')
        <section class="panel mb-4">
            <div class="panel-header">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-people" aria-hidden="true"></i><span>Recent Users</span>
                    </h2>
                    <p class="text-muted mb-0">Latest account activity across the workspace.</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    Manage Users
                </a>
            </div>
            <div class="table-responsive">
                <!-- Tabel User tetap sama -->
            </div>
        </section>
        @endif

    </div>
</main>
@endsection