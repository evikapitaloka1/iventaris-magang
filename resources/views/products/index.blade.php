@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- HEADING & ACTIONS --}}
        <div class="page-heading d-flex justify-content-between align-items-center mb-4">
            <div class="page-heading-copy d-flex align-items-center gap-3">
                <span class="page-icon bg-primary text-white p-2 rounded">
                    <i class="bi bi-box-seam fs-4"></i>
                </span>
                <div>
                    <p class="eyebrow mb-0 text-uppercase fw-bold text-muted" style="font-size: 0.75rem;">Inventory</p>
                    <h1 class="h3 mb-0">Products</h1>
                    <p class="text-muted mb-0 small">
                        Kelola data barang, stok, kategori, dan kondisi barang.
                    </p>
                </div>
            </div>

            <div class="heading-actions d-flex gap-2">
                {{-- Dropdown Export --}}
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <ul class="dropdown-menu shadow-sm border-0">
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('products.export.excel') }}">
                                <i class="bi bi-file-earmark-excel text-success me-2"></i> Export to Excel
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="{{ route('products.export.pdf') }}">
                                <i class="bi bi-file-earmark-pdf text-danger me-2"></i> Export to PDF
                            </a>
                        </li>
                    </ul>
                </div>

                <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i class="bi bi-plus-circle"></i> Add Product
                </a>
            </div>
        </div>

        {{-- CARD SUMMARY --}}
        <section class="row g-3 mt-1 mb-4">
            <div class="col-md-3">
                <div class="metric-card panel p-3 border-start border-4 border-primary shadow-sm h-100 rounded">
                    <div class="metric-top d-flex justify-content-between text-muted mb-2">
                        <span class="small fw-semibold">Total Products</span>
                        <i class="bi bi-box"></i>
                    </div>
                    <!-- Hapus text-dark -->
                    <div class="metric-value fs-3 fw-bold">
                        {{ $products->total() }}
                    </div>
                    <div class="metric-meta small text-muted">
                        Registered Items
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card panel p-3 border-start border-4 border-success shadow-sm h-100 rounded">
                    <div class="metric-top d-flex justify-content-between text-muted mb-2">
                        <span class="small fw-semibold">Total Stock</span>
                        <i class="bi bi-boxes"></i>
                    </div>
                    {{-- Menghitung total stok dari koleksi paginasi --}}
                    <!-- Hapus text-dark -->
                    <div class="metric-value fs-3 fw-bold">
                        {{ \App\Models\Product::sum('stock') }} 
                    </div>
                    <div class="metric-meta small text-muted">
                        Items in Inventory
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card panel p-3 border-start border-4 border-warning shadow-sm h-100 rounded">
                    <div class="metric-top d-flex justify-content-between text-muted mb-2">
                        <span class="small fw-semibold">Low Stock</span>
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <!-- Hapus text-dark -->
                    <div class="metric-value fs-3 fw-bold">
                        {{ \App\Models\Product::where('stock', '<=', 5)->count() }}
                    </div>
                    <div class="metric-meta small text-muted">
                        Stock <= 5
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="metric-card panel p-3 border-start border-4 border-danger shadow-sm h-100 rounded">
                    <div class="metric-top d-flex justify-content-between text-muted mb-2">
                        <span class="small fw-semibold">Out of Stock</span>
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <!-- Hapus text-dark -->
                    <div class="metric-value fs-3 fw-bold">
                        {{ \App\Models\Product::where('stock', 0)->count() }}
                    </div>
                    <div class="metric-meta small text-muted">
                        Empty Inventory
                    </div>
                </div>
            </div>
        </section>

        {{-- TABLE SECTION --}}
        <!-- Hapus bg-white pada tag section di bawah -->
        <section class="panel shadow-sm rounded border">
            
            {{-- Panel Header dengan Title & Search Bar --}}
            <div class="panel-header p-3 border-bottom d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h2 class="h6 mb-0 fw-bold">Product List</h2>
                </div>

                <div class="d-flex flex-column flex-md-row gap-2">
                    {{-- Form Pencarian --}}
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex">
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Search name or code..." value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="{{ route('products.index') }}" class="btn btn-outline-danger" title="Clear Search">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table Responsive --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <!-- Hapus table-light pada thead -->
                    <thead>
                        <tr>
                            <th class="ps-3">Image</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Stock</th>
                            <th>Location</th>
                            <th>Condition</th>
                            <th class="text-center" width="150">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-3">
                                    @if($product->image_path)
                                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                                             alt="{{ $product->name }}" 
                                             class="img-thumbnail border-0 shadow-sm" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <!-- Hapus bg-light -->
                                        <div class="text-secondary d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px; font-size: 10px; border-radius: 8px;">
                                            No Image
                                        </div>
                                    @endif
                                </td>
                                <!-- Hapus bg-light dan text-dark, ganti dengan text-body -->
                                <td><span class="badge text-body border">{{ $product->product_code }}</span></td>
                                <td class="fw-bold">
                                    <!-- Hapus text-dark -->
                                    <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-body">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td>
                                    @if($product->category)
                                        <span class="badge text-bg-info text-white">{{ $product->category->name }}</span>
                                    @else
                                        <span class="badge text-bg-secondary">Uncategorized</span>
                                    @endif
                                </td>
                                <td>
                                    @if($product->stock > 5)
                                        <span class="text-success fw-bold">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="text-warning fw-bold">{{ $product->stock }}</span>
                                    @else
                                        <span class="text-danger fw-bold">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>{{ $product->location }}</td>
                                <td>
                                    <!-- Hapus bg-light -->
                                    <span class="badge text-secondary border">{{ $product->condition }}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm">
                                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-light btn-sm border" title="Detail">
                                            <i class="bi bi-eye text-info"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-light btn-sm border" title="Edit">
                                            <i class="bi bi-pencil-square text-warning"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-light btn-sm border" onclick="return confirm('Are you sure you want to delete this product?')" title="Delete" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                <i class="bi bi-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                                        <p class="mt-2 mb-0 fw-medium">No Products Found</p>
                                        <small>Try adjusting your search keyword or add a new product.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Bagian Pagination --}}
            @if($products->hasPages())
                <!-- Hapus bg-light dari panel-footer -->
                <div class="panel-footer p-3 border-top d-flex flex-column flex-md-row justify-content-between align-items-center rounded-bottom">
                    <p class="text-muted mb-2 mb-md-0 small">
                        Showing <span class="fw-bold">{{ $products->firstItem() }}</span> to <span class="fw-bold">{{ $products->lastItem() }}</span> of <span class="fw-bold">{{ $products->total() }}</span> results
                    </p>
                    <div class="pagination-sm m-0">
                        {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @endif

        </section>
    </div>
</main>

@endsection