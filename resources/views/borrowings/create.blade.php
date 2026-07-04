@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-plus-circle"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Transaction</p>
                    <h1 class="h3 mb-1">Add Borrowing</h1>
                    <p class="text-muted mb-0">
                        Submit a new borrowing request. It will need admin approval before the stock is reduced.
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

        <form action="{{ route('borrowings.store') }}" method="POST" class="panel mt-3">

            @csrf

            <div class="panel-header">
                <h2 class="h5 mb-0">Borrowing Request Form</h2>
            </div>

            <div class="p-4">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Product <span class="text-danger">*</span>
                        </label>

                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror">
                            <option value="">-- Select Product --</option>
                            @forelse($products as $product)
                                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                </option>
                            @empty
                                <option value="" disabled>No products available</option>
                            @endforelse
                        </select>

                        @error('product_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Quantity <span class="text-danger">*</span>
                        </label>

                        <input type="number"
                               name="qty"
                               min="1"
                               class="form-control @error('qty') is-invalid @enderror"
                               value="{{ old('qty', 1) }}">

                        @error('qty')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Stock availability is checked again when the admin approves this request.</small>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Borrow Date <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="borrow_date"
                               class="form-control @error('borrow_date') is-invalid @enderror"
                               value="{{ old('borrow_date', date('Y-m-d')) }}">

                        @error('borrow_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Due Date (Must Return By) <span class="text-danger">*</span>
                        </label>

                        <input type="date"
                               name="due_date"
                               class="form-control @error('due_date') is-invalid @enderror"
                               value="{{ old('due_date') }}">

                        @error('due_date')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                        <small class="text-muted">Item will be flagged as overdue if not returned by this date.</small>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Purpose <small class="text-muted">(optional)</small>
                        </label>

                        <input type="text"
                               name="purpose"
                               class="form-control @error('purpose') is-invalid @enderror"
                               value="{{ old('purpose') }}"
                               placeholder="e.g. Client presentation, field work, etc.">

                        @error('purpose')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror

                    </div>

                </div>

                <div class="alert alert-info mt-4 d-flex gap-2 align-items-start">
                    <i class="bi bi-info-circle-fill mt-1"></i>
                    <div>
                        Your request will be submitted with status <strong>Pending</strong>. Stock will only
                        be reduced once an admin approves it.
                    </div>
                </div>

                <div class="mt-4 text-end">

                    <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">
                        Cancel
                    </a>

                    <button class="btn btn-primary">
                        <i class="bi bi-send-check"></i>
                        Submit Request
                    </button>

                </div>

            </div>

        </form>

    </div>
</main>

@endsection