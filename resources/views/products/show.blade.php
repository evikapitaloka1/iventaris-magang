@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-info-square"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Inventory</p>
                    <h1 class="h3 mb-1">Product Details</h1>
                    <p class="text-muted mb-0">Informasi lengkap mengenai produk {{ $product->name }}.</p>
                </div>
            </div>

            <div class="heading-actions">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Back to List
                </a>
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil-square"></i>
                    Edit Product
                </a>
            </div>
        </div>

        <div class="row mt-4 g-4">
            {{-- Kolom Gambar --}}
            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-body p-4 d-flex flex-column align-items-center justify-content-center">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded shadow-sm" 
                                 style="width: 100%; max-height: 350px; object-fit: cover;">
                        @else
                            <div class="bg-light text-muted d-flex flex-column align-items-center justify-content-center rounded w-100" style="height: 350px;">
                                <i class="bi bi-image text-secondary" style="font-size: 4rem;"></i>
                                <span class="mt-2">No Image Available</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Kolom Informasi --}}
            <div class="col-lg-8">
                <div class="panel h-100">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">Overview</h2>
                    </div>
                    <div class="panel-body p-0">
                        <table class="table table-borderless table-striped mb-0">
                            <tbody>
                                <tr>
                                    <th class="ps-4 py-3 w-25 text-muted">Product Code</th>
                                    <td class="py-3">
                                        <span class="badge bg-light text-dark border">{{ $product->product_code }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Product Name</th>
                                    <td class="py-3 fw-bold fs-5 text-dark">{{ $product->name }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Category</th>
                                    <td class="py-3">
                                        @if($product->category)
                                            <span class="badge text-bg-info text-white">{{ $product->category->name }}</span>
                                        @else
                                            <span class="badge text-bg-secondary">Uncategorized</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Stock Status</th>
                                    <td class="py-3">
                                        @if($product->stock > 5)
                                            <span class="badge bg-success px-3">{{ $product->stock }} Units Available</span>
                                        @elseif($product->stock > 0)
                                            <span class="badge bg-warning text-dark px-3">{{ $product->stock }} Low Stock</span>
                                        @else
                                            <span class="badge bg-danger px-3">Out of Stock</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Location</th>
                                    <td class="py-3">
                                        <i class="bi bi-geo-alt me-1 text-secondary"></i> {{ $product->location }}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Condition</th>
                                    <td class="py-3">{{ $product->condition }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-4 py-3 text-muted">Date Added</th>
                                    <td class="py-3">{{ $product->created_at->format('d M Y, H:i') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

@endsection