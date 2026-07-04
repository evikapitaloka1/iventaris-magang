@if($lowStockProducts->count() > 0)
<div class="alert alert-warning d-flex gap-3 align-items-start mb-4">

    <i class="bi bi-box-seam fs-4"></i>

    <div class="flex-grow-1">

        <p class="fw-semibold mb-1">
            {{ $lowStockProducts->count() }} product(s) are running low on stock!
        </p>

        <ul class="mb-0 ps-3 small">
            @foreach($lowStockProducts->take(5) as $product)
                <li>
                    <a href="{{ route('products.show', $product->id) }}" class="alert-link">
                        {{ $product->name }}
                    </a>
                    — only <strong>{{ $product->stock }}</strong> left
                </li>
            @endforeach
        </ul>

        @if($lowStockProducts->count() > 5)
            <p class="small mb-0 mt-1">
                and {{ $lowStockProducts->count() - 5 }} more...
            </p>
        @endif

    </div>

    <a href="{{ route('products.index') }}" class="btn btn-warning btn-sm">
        View Products
    </a>

</div>
@endif