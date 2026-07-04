@extends('layouts.app')

@section('content')

<main class="dashboard-content">
    <div class="container-fluid px-3 px-lg-4 py-4">

        {{-- Heading --}}
        <div class="page-heading">
            <div class="page-heading-copy">
                <span class="page-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>

                <div>
                    <p class="eyebrow mb-1">Inventory</p>
                    <h1 class="h3 mb-1">Edit Product: {{ $product->name }}</h1>
                    <p class="text-muted mb-0">Update information and stock for this product.</p>
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
                {{-- Form Edit --}}
                <form action="{{ route('products.update', $product->id) }}" 
                      method="POST" 
                      class="panel" 
                      enctype="multipart/form-data">
                    
                    @csrf
                    @method('PUT') {{-- Wajib menggunakan PUT atau PATCH untuk update --}}

                    <div class="panel-header">
                        <div>
                            <h2 class="h5 mb-1 section-title">
                                <i class="bi bi-info-circle"></i>
                                Product Information
                            </h2>
                            <p class="text-muted mb-0">Update the required product details.</p>
                        </div>
                    </div>

                    <div class="panel-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Product Code <span class="text-danger">*</span></label>
                                <input type="text" name="product_code" class="form-control @error('product_code') is-invalid @enderror" value="{{ old('product_code', $product->product_code) }}" required>
                                @error('product_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                                    <option value="">Choose Category</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Stock <span class="text-danger">*</span></label>
                                <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" min="0" required>
                                @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $product->location) }}" required>
                                @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Condition <span class="text-danger">*</span></label>
                                <select class="form-select @error('condition') is-invalid @enderror" name="condition" required>
                                    <option value="">Choose Condition</option>
                                    <option value="New" {{ old('condition', $product->condition) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Good" {{ old('condition', $product->condition) == 'Good' ? 'selected' : '' }}>Good (Second)</option>
                                    <option value="Damaged" {{ old('condition', $product->condition) == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                                </select>
                                @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Product Image</label>
                                
                                {{-- Preview Image Lama --}}
                                @if($product->image_path)
                                    <div class="mb-3">
                                        <p class="text-muted small mb-1">Current Image:</p>
                                        <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="img-thumbnail" style="height: 120px; object-fit: cover;">
                                    </div>
                                @endif

                                <input type="file" name="image_path" class="form-control @error('image_path') is-invalid @enderror" accept="image/*">
                                <div class="form-text">Biarkan kosong jika tidak ingin mengubah gambar. Max size: 2MB.</div>
                                @error('image_path') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="panel-footer mt-4 text-end p-4 border-top">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button class="btn btn-primary" type="submit"><i class="bi bi-save"></i> Update Product</button>
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
                            <li class="list-group-item">✔ You are currently editing <strong>{{ $product->product_code }}</strong>.</li>
                            <li class="list-group-item">✔ If you upload a new image, the old image will be automatically replaced.</li>
                            <li class="list-group-item text-danger">✔ Fields marked with (*) are required.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

    </div>
</main>
@endsection