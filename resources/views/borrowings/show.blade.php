@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Transaction</p>
                    <h1 class="h3 mb-1">Borrowing Detail</h1>
                    <p class="text-muted mb-0">
                        Full information and status history for this borrowing record.
                    </p>
                </div>
            </div>

            <div class="heading-actions">

                @if($borrowing->status === 'pending' && auth()->user()->role?->name === 'Admin')

                    <form action="{{ route('borrowings.approve', $borrowing->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>

                    <form action="{{ route('borrowings.reject', $borrowing->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman ini?')">
                            <i class="bi bi-x-circle"></i> Reject
                        </button>
                    </form>

                @elseif($borrowing->status === 'borrowed' && auth()->user()->role?->name === 'Admin')

                    <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-box-arrow-in-left"></i> Process Return
                    </a>

                @endif

                <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

            </div>
        </div>

        <section class="row g-3 mt-1">

            {{-- Info utama --}}
            <div class="col-12 col-xl-8">

                <div class="panel">

                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1">Borrowing Information</h2>
                            <p class="text-muted mb-0">Borrower details and requested items.</p>
                        </div>

                        @switch($borrowing->status)
                            @case('pending')
                                <span class="badge text-bg-warning fs-6">Pending</span>
                                @break
                            @case('borrowed')
                                <span class="badge text-bg-primary fs-6">Borrowed</span>
                                @break
                            @case('rejected')
                                <span class="badge text-bg-danger fs-6">Rejected</span>
                                @break
                            @case('returned')
                                <span class="badge text-bg-success fs-6">Returned</span>
                                @break
                            @default
                                <span class="badge text-bg-secondary fs-6">{{ ucfirst($borrowing->status) }}</span>
                        @endswitch
                    </div>

                    <div class="p-4">

                        <div class="row g-3 mb-4">

                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Borrower</p>
                                <p class="fw-semibold mb-0">{{ $borrowing->user->name ?? 'Unknown User' }}</p>
                                <p class="text-muted small mb-0">{{ $borrowing->user->email ?? '-' }}</p>
                            </div>

                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Borrow Date</p>
                                <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</p>
                            </div>

                            <div class="col-md-4">
                                <p class="text-muted small mb-1">Return Date</p>
                                <p class="fw-semibold mb-0">
                                    @if($borrowing->return_date)
                                        {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                    @else
                                        <span class="text-muted">Not returned yet</span>
                                    @endif
                                </p>
                            </div>

                            @if($borrowing->purpose)
                                <div class="col-12">
                                    <p class="text-muted small mb-1">Purpose</p>
                                    <p class="fw-semibold mb-0">{{ $borrowing->purpose }}</p>
                                </div>
                            @endif

                            @if($borrowing->status === 'rejected' && $borrowing->rejection_reason)
                                <div class="col-12">
                                    <div class="alert alert-danger mb-0">
                                        <strong>Rejection Reason:</strong> {{ $borrowing->rejection_reason }}
                                    </div>
                                </div>
                            @endif

                        </div>

                        <h3 class="h6 mb-3">Items</h3>

                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Condition</th>
                                        <th>Photo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($borrowing->details as $detail)
                                        <tr>
                                            <td>{{ $detail->product->name ?? 'Unknown Product' }}</td>
                                            <td>{{ $detail->qty }}</td>
                                            <td>
                                                @if($detail->item_status)
                                                    {{ $detail->item_status }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($detail->photo)
                                                    <a href="{{ asset('storage/' . $detail->photo) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $detail->photo) }}"
                                                             alt="Item condition"
                                                             style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Timeline status --}}
            <div class="col-12 col-xl-4">

                <div class="panel h-100">

                    <div class="panel-header">
                        <h2 class="h5 mb-0">Status Timeline</h2>
                    </div>

                    <div class="p-4">

                        <ul class="list-unstyled mb-0">

                            <li class="d-flex gap-3 mb-4">
                                <span class="badge rounded-circle text-bg-success p-2"><i class="bi bi-check"></i></span>
                                <div>
                                    <p class="fw-semibold mb-0">Request Submitted</p>
                                    <p class="text-muted small mb-0">{{ $borrowing->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </li>

                            @if($borrowing->status === 'rejected')

                                <li class="d-flex gap-3 mb-4">
                                    <span class="badge rounded-circle text-bg-danger p-2"><i class="bi bi-x"></i></span>
                                    <div>
                                        <p class="fw-semibold mb-0">Rejected</p>
                                        <p class="text-muted small mb-0">{{ $borrowing->updated_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </li>

                            @else

                                <li class="d-flex gap-3 mb-4">
                                    <span class="badge rounded-circle p-2 {{ in_array($borrowing->status, ['borrowed', 'returned']) ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        <i class="bi {{ in_array($borrowing->status, ['borrowed', 'returned']) ? 'bi-check' : 'bi-clock' }}"></i>
                                    </span>
                                    <div>
                                        <p class="fw-semibold mb-0">Approved &amp; Borrowed</p>
                                        <p class="text-muted small mb-0">
                                            @if(in_array($borrowing->status, ['borrowed', 'returned']))
                                                Stock reduced
                                            @else
                                                Waiting for admin approval
                                            @endif
                                        </p>
                                    </div>
                                </li>

                                <li class="d-flex gap-3">
                                    <span class="badge rounded-circle p-2 {{ $borrowing->status === 'returned' ? 'text-bg-success' : 'text-bg-secondary' }}">
                                        <i class="bi {{ $borrowing->status === 'returned' ? 'bi-check' : 'bi-clock' }}"></i>
                                    </span>
                                    <div>
                                        <p class="fw-semibold mb-0">Returned</p>
                                        <p class="text-muted small mb-0">
                                            @if($borrowing->status === 'returned')
                                                {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }} — Stock restored
                                            @else
                                                Not yet returned
                                            @endif
                                        </p>
                                    </div>
                                </li>

                            @endif

                        </ul>

                    </div>

                </div>

            </div>

        </section>

    </div>
</main>

@endsection