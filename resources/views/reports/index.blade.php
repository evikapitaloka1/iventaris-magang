@extends('layouts.app')

@section('content')
<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <!-- 1. Page Heading -->
        <div class="page-heading d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
            <div class="page-heading-copy d-flex align-items-center gap-3">
                <span class="page-icon">
                    <i class="bi bi-file-earmark-bar-graph fs-3 text-primary" aria-hidden="true"></i>
                </span>
                <div>
                    <p class="eyebrow mb-1 text-muted">Analytics & Overview</p>
                    <h1 class="h3 mb-1">Laporan Peminjaman</h1>
                    <p class="text-muted mb-0">Pantau dan unduh rekam jejak peminjaman barang inventaris.</p>
                </div>
            </div>
            
            <!-- Tombol Export -->
            <div class="heading-actions d-flex gap-2">
    <!-- NAMA ROUTE SUDAH DISESUAIKAN -->
    <a href="{{ route('borrowings.export.excel') }}" class="btn btn-outline-success btn-sm">
        <i class="bi bi-file-earmark-spreadsheet" aria-hidden="true"></i> Export Excel
    </a>
    <a href="{{ route('borrowings.export.pdf') }}" class="btn btn-outline-danger btn-sm">
        <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i> Export PDF
    </a>
</div>
        </div>

        <!-- 2. Filter Section -->
        <div class="panel mb-4 shadow-sm bg-white rounded">
            <div class="panel-header border-bottom p-3">
                <h2 class="h6 mb-0"><i class="bi bi-funnel me-2"></i>Filter Laporan</h2>
            </div>
            <div class="panel-body p-3">
                <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <label for="start_date" class="form-label small text-muted fw-bold">Dari Tanggal</label>
                        <input type="date" class="form-control form-control-sm" id="start_date" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="end_date" class="form-label small text-muted fw-bold">Sampai Tanggal</label>
                        <input type="date" class="form-control form-control-sm" id="end_date" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="status" class="form-label small text-muted fw-bold">Status Peminjaman</label>
                        <select class="form-select form-select-sm" id="status" name="status">
                            <option value="">-- Semua Status --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                            <i class="bi bi-search me-1"></i> Terapkan
                        </button>
                        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- 3. Summary Metrics -->
        <section class="row g-3 mb-4">
            <div class="col-6 col-xl-3">
                <article class="metric-card p-3 shadow-sm bg-white rounded border-start border-primary border-4">
                    <span class="text-muted small fw-bold d-block mb-1">Total Peminjaman</span>
                    <span class="fs-4 fw-bold text-dark">{{ $totalBorrowings }}</span>
                </article>
            </div>
            <div class="col-6 col-xl-3">
                <article class="metric-card p-3 shadow-sm bg-white rounded border-start border-warning border-4">
                    <span class="text-muted small fw-bold d-block mb-1">Sedang Dipinjam</span>
                    <span class="fs-4 fw-bold text-warning">{{ $totalBorrowed }}</span>
                </article>
            </div>
            <div class="col-6 col-xl-3">
                <article class="metric-card p-3 shadow-sm bg-white rounded border-start border-success border-4">
                    <span class="text-muted small fw-bold d-block mb-1">Sudah Dikembalikan</span>
                    <span class="fs-4 fw-bold text-success">{{ $totalReturned }}</span>
                </article>
            </div>
            <div class="col-6 col-xl-3">
                <article class="metric-card p-3 shadow-sm bg-white rounded border-start border-info border-4">
                    <span class="text-muted small fw-bold d-block mb-1">Menunggu Approval</span>
                    <span class="fs-4 fw-bold text-info">{{ $totalPending }}</span>
                </article>
            </div>
        </section>

        <!-- 4. Data Table -->
        <section class="panel mb-4 shadow-sm bg-white rounded">
            <div class="panel-header border-bottom p-3">
                <h2 class="h6 mb-0"><i class="bi bi-table me-2"></i>Rincian Data Peminjaman</h2>
            </div>
            <div class="table-responsive p-3">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Peminjam</th>
                            <th>Barang & Qty</th>
                            <th>Tgl Pinjam</th>
                            <th>Tgl Kembali / Jatuh Tempo</th>
                            <th>Status</th>
                            <th>Keperluan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($borrowings as $index => $item)
                        <tr>
                            <td>{{ $borrowings->firstItem() + $index }}</td>
                            <td>
                                <div class="fw-medium">{{ $item->user->name ?? 'Unknown' }}</div>
                                <small class="text-muted">{{ $item->user->email ?? '-' }}</small>
                            </td>
                            <td>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach($item->details as $detail)
                                        <li>• {{ $detail->product->name ?? 'Barang Dihapus' }} <strong>(x{{ $detail->qty }})</strong></li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->borrow_date)->format('d M Y') }}</td>
                            <td>
                                @if($item->return_date)
                                    <span class="text-success fw-medium">{{ \Carbon\Carbon::parse($item->return_date)->format('d M Y') }}</span>
                                    <small class="d-block text-muted">(Dikembalikan)</small>
                                @else
                                    <span class="text-danger">{{ \Carbon\Carbon::parse($item->due_date)->format('d M Y') }}</span>
                                    <small class="d-block text-muted">(Jatuh Tempo)</small>
                                @endif
                            </td>
                            <td>
                                @if($item->status === 'pending')
                                    <span class="badge text-bg-warning">Pending</span>
                                @elseif($item->status === 'borrowed')
                                    <span class="badge text-bg-primary">Dipinjam</span>
                                @elseif($item->status === 'returned')
                                    <span class="badge text-bg-success">Dikembalikan</span>
                                @else
                                    <span class="badge text-bg-danger">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="{{ $item->purpose }}">
                                    {{ $item->purpose ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-3 d-block mb-1"></i>
                                Tidak ada data laporan yang sesuai dengan filter.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($borrowings->hasPages())
            <div class="panel-footer border-top p-3 d-flex justify-content-end">
                {{ $borrowings->links() }}
            </div>
            @endif
        </section>

    </div>
</main>
@endsection