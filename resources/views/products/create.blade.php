@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-box-seam"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Inventory</p>
                    <h1 class="h3 mb-1">Add Product</h1>
                    <p class="text-muted mb-0">Register a new product to the inventory.</p>
                </div>
            </div>

            <div class="heading-actions">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i>
                    Back
                </a>
            </div>
        </div>

        <section class="row g-3">
            <div class="col-lg-8">
                {{-- Form Create --}}
                <form action="{{ route('products.store') }}" 
                      method="POST" 
                      class="panel" 
                      enctype="multipart/form-data">
                    @csrf

                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-info-circle"></i>
                                Product Information
                            </h2>
                            <p class="text-muted mb-0">Fill in all required product details.</p>
                        </div>
                    </div>

                    <div class="panel-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code') }}" required>
                                @error('product_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Choose Category</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
                                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Condition <span class="text-danger">*</span></label>
                                <select class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                                    <option value="">Choose Condition</option>
                                    <option value="New" {{ old('condition') == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good (Second)</option>
                                    <option value="Damaged" {{ old('condition') == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>
                                @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Product Image</label>
                                <input type="file" name="image_path" class="form-control @error('image_path') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Format: JPG, PNG, JPEG. Max size: 2MB.</div>
                                @error('image_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer mt-4 text-end p-4 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Save Product</button>
                    </div>
                </form>
            </div>

            <div class="col-lg-4">
                <div class="panel h-100">
                    <div class="panel-header">
                        <h2 class="h5 mb-0">Information</h2>
                    </div>
                    <div class="panel-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">✔ <strong>Product Code</strong> must be unique.</li>
                            <li class="list-group-item">✔ Select appropriate <strong>Category</strong>.</li>
                            <li class="list-group-item">✔ <strong>Image</strong> upload is optional.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>
@endsection