@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-box-arrow-in-left"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Transaction</p>
                    <h1 class="h3 mb-1">Process Return</h1>
                    <p class="text-muted mb-0">
                        Record the return date and condition of the borrowed item(s).
                    </p>
                </div>
            </div>

            <div class="heading-actions">
                <a href="{{ route('borrowings.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        {{-- Ringkasan peminjaman, read-only --}}
        <div class="panel mt-3">

            <div class="panel-header">
                <h2 class="h5 mb-0">Borrowing Summary</h2>
            </div>

            <div class="p-4">

                <div class="row g-3 mb-3">

                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Borrower</p>
                        <p class="fw-semibold mb-0">{{ $borrowing->user->name ?? 'Unknown User' }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Borrow Date</p>
                        <p class="fw-semibold mb-0">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</p>
                    </div>

                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Status</p>
                        <span class="badge text-bg-primary">{{ ucfirst($borrowing->status) }}</span>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($borrowing->details as $detail)
                                <tr>
                                    <td>{{ $detail->product->name ?? 'Unknown Product' }}</td>
                                    <td>{{ $detail->qty }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        {{-- Form pengembalian --}}
        <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" class="panel mt-3" enctype="multipart/form-data">

            @csrf
            @method('PATCH')

            <div class="panel-header">
                <h2 class="h5 mb-0">Return Details</h2>
            </div>

            <div class="p-4">

                <div class="row g-3 mb-4">

                    <div class="col-md-6">

                        <label class="form-label">
                            Return Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="return_date"
                               class="form-control @error('return_date') is-invalid @enderror"
                               value="{{ old('return_date', date('Y-m-d')) }}"
                               min="{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('Y-m-d') }}">

                        @error('return_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <hr>

                <h3 class="h6 mb-3">Item Condition &amp; Photo</h3>

                @foreach($borrowing->details as $detail)
                    <div class="row g-3 align-items-start mb-4 pb-3 border-bottom">

                        <div class="col-md-3">
                            <p class="fw-semibold mb-0">{{ $detail->product->name ?? 'Unknown Product' }}</p>
                            <p class="text-muted small mb-0">Qty: {{ $detail->qty }}</p>
                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Condition <span class="text-danger">*</span>
                            </label>

                            <select name="item_status[{{ $detail->id }}]"
                                    class="form-select @error("item_status.$detail->id") is-invalid @enderror">
                                <option value="">-- Select Condition --</option>
                                <option value="Baik">Baik (Good)</option>
                                <option value="Rusak Ringan">Rusak Ringan (Minor Damage)</option>
                                <option value="Rusak Berat">Rusak Berat (Major Damage)</option>
                                <option value="Hilang">Hilang (Lost)</option>
                            </select>

                            @error("item_status.$detail->id")
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>

                        <div class="col-md-5">

                            <label class="form-label">
                                Photo <small class="text-muted">(optional, max 2MB)</small>
                            </label>

                            <input type="file"
                                   name="photo[{{ $detail->id }}]"
                                   accept="image/png, image/jpeg"
                                   class="form-control @error("photo.$detail->id") is-invalid @enderror">

                            @error("photo.$detail->id")
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>
                @endforeach

                <div class="alert alert-info mt-2 d-flex gap-2 align-items-start">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        Confirming this return will mark the borrowing as <strong>Returned</strong> and
                        automatically add the quantity back to stock.
                    </div>
                </div>

                <div class="mt-4 text-end">

                    <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button class="btn btn-success" onclick="return confirm('Confirm this item has been returned?')">
                        <i class="bi bi-check2-circle"></i>
                        Confirm Return
                    </button>

                </div>

            </div>

        </form>

    </div>
</main>

@endsection