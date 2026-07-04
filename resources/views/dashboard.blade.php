@extends('layouts.app')

@section('content')

{{-- Tampilkan alert stok menipis HANYA UNTUK ADMIN --}}
@if(auth()->user()->role?->name === 'Admin')
    @include('profile.partials.low-stock-alert') 
@endif

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- 1. Page Heading (Muncul untuk semua User) -->
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

        <!-- 2. Dynamic User Information Panel (Muncul untuk semua User) -->
        <div class="panel mb-4 shadow-sm bg-white rounded">
            <div class="panel-header border-bottom p-3">
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
            
            <!-- Total Jenis Barang -->
            <div class="col-12 col-sm-6 col-xl-4">
                <article class="metric-card metric-primary p-3 shadow-sm bg-white rounded border-start border-primary border-4">
                    <div class="metric-top d-flex justify-content-between align-items-center mb-2">
                        <span class="metric-label text-muted fw-bold">Total Jenis Barang</span>
                        <span class="metric-icon fs-4 text-primary"><i class="bi bi-box-seam"></i></span>
                    </div>
                    <div class="metric-value fs-3 fw-bold">{{ $totalBarang }}</div>
                </article>
            </div>

            <!-- Barang Tersedia -->
            <div class="col-12 col-sm-6 col-xl-4">
                <article class="metric-card metric-success p-3 shadow-sm bg-white rounded border-start border-success border-4">
                    <div class="metric-top d-flex justify-content-between align-items-center mb-2">
                        <span class="metric-label text-muted fw-bold">Barang Tersedia</span>
                        <span class="metric-icon fs-4 text-success"><i class="bi bi-check2-circle"></i></span>
                    </div>
                    <div class="metric-value fs-3 fw-bold">{{ $barangTersedia }}</div>
                </article>
            </div>

            <!-- Barang Dipinjam -->
            <div class="col-12 col-sm-6 col-xl-4">
                <article class="metric-card metric-warning p-3 shadow-sm bg-white rounded border-start border-warning border-4">
                    <div class="metric-top d-flex justify-content-between align-items-center mb-2">
                        <span class="metric-label text-muted fw-bold">Barang Dipinjam</span>
                        <span class="metric-icon fs-4 text-warning"><i class="bi bi-cart-dash"></i></span>
                    </div>
                    <div class="metric-value fs-3 fw-bold">{{ $barangDipinjam }}</div>
                </article>
            </div>

        </section>
        
        <!-- 4. Charts & Team Activity (Hanya untuk Admin dan Manager) -->
        <section class="row g-3 mb-4">
            <div class="col-12">
                <div class="panel shadow-sm bg-white rounded">
                    <div class="panel-header border-bottom p-3">
                        <h2 class="h5 mb-0"><i class="bi bi-bar-chart-line me-2"></i>Grafik Peminjaman per Bulan ({{ date('Y') }})</h2>
                    </div>
                    <div class="panel-body p-4">
                        <!-- Canvas untuk Chart.js -->
                        <canvas id="borrowingChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- 5. Recent Users Table (HANYA UNTUK ADMIN) --}}
        @if(auth()->user()->role?->name === 'Admin')
        <section class="panel mb-4 shadow-sm bg-white rounded">
            <div class="panel-header border-bottom p-3 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="h5 mb-1 section-title">
                        <i class="bi bi-people me-2" aria-hidden="true"></i><span>Recent Users</span>
                    </h2>
                    <p class="text-muted mb-0 small">Latest account activity across the workspace.</p>
                </div>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                    Manage Users
                </a>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers as $user)
                        <tr>
                            <td class="fw-medium">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge text-bg-info">{{ $user->role?->name ?? 'N/A' }}</span></td>
                            <td class="text-muted">{{ $user->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No recent users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        @endif

    </div>
</main>

<!-- Script untuk render Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil data chart dari controller via Blade
        const rawChartData = @json($chartData);
        
        const ctx = document.getElementById('borrowingChart');
        
        if(ctx) {
            new Chart(ctx.getContext('2d'), {
                type: 'line', // Bisa diganti 'bar' jika ingin grafik batang
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'],
                    datasets: [{
                        label: 'Total Peminjaman',
                        data: rawChartData,
                        backgroundColor: 'rgba(13, 110, 253, 0.2)', // Warna Biru Bootstrap
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3, // Membuat garis melengkung halus
                        pointBackgroundColor: 'rgba(13, 110, 253, 1)',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1 // Angka peminjaman selalu bilangan bulat
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    }
                }
            });
        }
    });
</script>
@endsection