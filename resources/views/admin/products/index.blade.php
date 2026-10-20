@extends('layouts.admin')

@section('title', 'Manage products · BSR')
@section('section', 'Products')

@section('content')
<main class="admin-page">
    <div class="admin-heading"><div><span class="eyebrow">STORE MANAGEMENT</span><h1>Products</h1><p>Create and manage the products shown in your storefront.</p></div><a class="button button-dark" href="{{ route('admin.products.create') }}">New product <span>+</span></a></div>

    @if (session('status'))<div class="admin-success">{{ session('status') }}</div>@endif
    @if ($errors->any())<div class="form-error-summary">{{ $errors->first() }}</div>@endif

    <form class="admin-product-filters" method="GET" action="{{ route('admin.products.index') }}">
        <label>Search products<input type="search" name="search" value="{{ request('search') }}" placeholder="Product name or SKU"></label>
        <label>Status<select name="status"><option value="">All products</option><option value="active" @selected(request('status') === 'active')>Active</option><option value="draft" @selected(request('status') === 'draft')>Draft</option></select></label>
        <button class="button button-dark" type="submit">Apply filters</button>
        <a class="text-button" href="{{ route('admin.products.index') }}">Clear</a>
    </form>

    <section class="admin-card admin-product-table-card">
        <div class="admin-table-scroll"><table class="admin-product-table">
            <thead><tr><th>Product</th><th>Price</th><th>Stock</th><th>Options</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td><div class="admin-product-identity"><img src="{{ $product->main_image_url }}" alt="" loading="lazy"><div><strong>{{ $product->name }}</strong><span>{{ $product->category }} · {{ $product->sku }}</span>@if ($product->featured)<small>Featured</small>@endif</div></div></td>
                        <td><strong>৳{{ number_format($product->discount_price ?? $product->regular_price) }}</strong>@if ($product->discount_price)<del>৳{{ number_format($product->regular_price) }}</del>@endif</td>
                        <td>{{ number_format($product->stock_quantity) }}</td>
                        <td>{{ $product->variants_count }} variants<br><small>{{ $product->images_count }} images</small></td>
                        <td><span class="admin-status admin-status-{{ $product->status }}">{{ ucfirst($product->status) }}</span></td>
                        <td><div class="admin-row-actions"><a href="{{ route('admin.products.edit', $product) }}">Edit</a><form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Delete {{ addslashes($product->name) }}?')">@csrf @method('DELETE')<button type="submit">Delete</button></form></div></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="admin-empty-row">No products match these filters.</td></tr>
                @endforelse
            </tbody>
        </table></div>
        <div class="admin-pagination">{{ $products->links() }}</div>
    </section>
</main>
@endsection
