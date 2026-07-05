@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Transaction</p>
                    <h1 class="h3 mb-1">Borrowings</h1>
                    <p class="text-muted mb-0">
                        Manage borrowing records, return dates, and transaction statuses.
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('borrowings.export.excel') }}">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i> Export to Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('borrowings.export.pdf') }}">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Export to PDF
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('borrowings.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle"></i>
                    Add Borrowing
                </a>
            </div>
        </div>

        {{-- CARD SUMMARY --}}
        <section class="row g-3 mt-1">

            <div class="col-md-3">
                <div class="metric-card metric-primary">
                    <div class="metric-top">
                        <span>Total Borrowings</span>
                        <i class="bi bi-collection"></i>
                    </div>

                    <div class="metric-value">
                        {{ $borrowings->total() }}
                    </div>

                    <div class="metric-meta">
                        All Transactions
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-warning">
                    <div class="metric-top">
                        <span>Pending</span>
                        <i class="bi bi-hourglass-split"></i>
                    </div>

                    <div class="metric-value">
                        {{ $borrowings->where('status', 'pending')->count() }}
                    </div>

                    <div class="metric-meta">
                        Waiting approval
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-primary">
                    <div class="metric-top">
                        <span>Borrowed</span>
                        <i class="bi bi-box-seam"></i>
                    </div>

                    <div class="metric-value">
                        {{ $borrowings->where('status', 'borrowed')->count() }}
                    </div>

                    <div class="metric-meta">
                        Currently out
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card metric-success">
                    <div class="metric-top">
                        <span>Returned</span>
                        <i class="bi bi-check-circle"></i>
                    </div>

                    <div class="metric-value">
                        {{ $borrowings->where('status', 'returned')->count() }}
                    </div>

                    <div class="metric-meta">
                        Completed transactions
                    </div>
                </div>
            </div>

        </section>

        {{-- TABLE --}}
        <section class="panel mt-3">

            <div class="panel-header">

                <div>
                    <h2 class="h5 mb-1">
                        Borrowing List
                    </h2>

                    <p class="text-muted mb-0">
                        Recent borrowing transactions.
                    </p>
                </div>

                

            </div>

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead>

                    <tr>
                        <th>Borrower</th>
                        <th>Items</th>
                        <th>Borrow Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th width="220">Action</th>
                    </tr>

                    </thead>

                    <tbody>

                    @forelse($borrowings as $borrowing)

                        <tr>
                            <td>{{ $borrowing->user->name ?? 'Unknown User' }}</td>

                            <td>
                                <span class="badge text-bg-light border">
                                    {{ $borrowing->details->count() }} Item(s)
                                </span>
                            </td>

                            <td>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</td>

                            <td>
                                @if($borrowing->return_date)
                                    {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            {{-- Status Badge, disesuaikan dengan alur Pending -> Borrowed/Rejected -> Returned --}}
                            <td>
                                @switch($borrowing->status)
                                    @case('pending')
                                        <span class="badge text-bg-warning">Pending</span>
                                        @break
                                    @case('borrowed')
                                        <span class="badge text-bg-primary">Borrowed</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge text-bg-danger">Rejected</span>
                                        @break
                                    @case('returned')
                                        <span class="badge text-bg-success">Returned</span>
                                        @break
                                    @default
                                        <span class="badge text-bg-secondary">{{ ucfirst($borrowing->status) }}</span>
                                @endswitch
                            </td>

                            {{-- Tombol aksi berubah sesuai status, hanya Admin yang bisa approve/reject/return --}}
                            <td>
                                <a href="{{ route('borrowings.show', $borrowing->id) }}" class="btn btn-light btn-sm">
                                    View
                                </a>

                                @if($borrowing->status === 'pending' && auth()->user()->role?->name === 'Admin')

                                    <form action="{{ route('borrowings.approve', $borrowing->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">
                                            Approve
                                        </button>
                                    </form>

                                    <form action="{{ route('borrowings.reject', $borrowing->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman ini?')">
                                            Reject
                                        </button>
                                    </form>

                                @elseif($borrowing->status === 'borrowed' && auth()->user()->role?->name === 'Admin')

                                    <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn btn-warning btn-sm">
                                        Process Return
                                    </a>

                                @else
                                    <span class="text-muted small">No action</span>
                                @endif
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="text-center">
                                No borrowing records found.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

            <div class="p-3">
                {{ $borrowings->links() }}
            </div>

        </section>

    </div>
</main>

@endsection