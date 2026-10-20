@extends('layouts.admin')

@section('title', ($product->exists ? 'Edit product' : 'New product').' · BSR')
@section('section', 'Products')

@section('content')
@php
    $variantRows = old('variants', $variants->map(fn ($variant) => $variant instanceof \App\Models\ProductVariant
        ? $variant->only(['id', 'name', 'value', 'sku', 'stock_quantity', 'price_adjustment', 'is_active'])
        : $variant)->all());
@endphp
<main class="admin-page">
    <div class="admin-heading"><div><span class="eyebrow">PRODUCT CATALOG</span><h1>{{ $product->exists ? 'Edit product' : 'Create product' }}</h1><p>Manage product details, images, stock, and options.</p></div><a class="text-button" href="{{ route('admin.products.index') }}">Back to products</a></div>

    @if ($errors->any())<div class="form-error-summary" role="alert">Please review the highlighted product details. {{ $errors->first() }}</div>@endif
    @if (session('status'))<div class="admin-success">{{ session('status') }}</div>@endif

    <form class="admin-product-form" method="POST" enctype="multipart/form-data" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}">
        @csrf
        @if ($product->exists) @method('PUT') @endif

        <section class="admin-card"><h2>Product information</h2>
            <div class="admin-form-grid">
                <label>Product name<input name="name" value="{{ old('name', $product->name) }}" maxlength="180" required></label>
                <label>Bangla name <span>(optional; shown on the storefront when provided)</span><input name="bangla_name" value="{{ old('bangla_name', $product->bangla_name) }}" maxlength="180" placeholder="বাংলা পণ্যের নাম"></label>
                <label>SKU<input name="sku" value="{{ old('sku', $product->sku) }}" maxlength="100" required></label>
                <label>URL slug <span>(leave blank to generate from name)</span><input name="slug" value="{{ old('slug', $product->slug) }}" maxlength="190"></label>
                <label>Category<input name="category" value="{{ old('category', $product->category) }}" placeholder="e.g. MEN" maxlength="100" required></label>
                <label>Regular price (BDT)<input name="regular_price" type="number" min="0" step="0.01" value="{{ old('regular_price', $product->regular_price) }}" required></label>
                <label>Discount price (BDT)<input name="discount_price" type="number" min="0" step="0.01" value="{{ old('discount_price', $product->discount_price) }}"></label>
                <label>Available stock<input name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}" required></label>
                <label>Status<select name="status" required><option value="active" @selected(old('status', $product->status ?? 'active') === 'active')>Active</option><option value="draft" @selected(old('status', $product->status ?? 'active') === 'draft')>Draft</option></select></label>
                <label>Material<input name="material" value="{{ old('material', $product->material) }}" maxlength="180"></label>
                <label>Quality note<input name="quality_note" value="{{ old('quality_note', $product->quality_note) }}" maxlength="500"></label>
                <label class="admin-checkbox-field"><input type="checkbox" name="featured" value="1" @checked(old('featured', $product->featured ?? false))> Show as a featured product</label>
            </div>
            <label class="admin-full-field">Short description<textarea name="short_description" rows="2" maxlength="500" required>{{ old('short_description', $product->short_description) }}</textarea></label>
            <label class="admin-full-field">Full description<textarea name="description" rows="5" required>{{ old('description', $product->description) }}</textarea></label>
            <label class="admin-full-field">Product features <span>(one feature per line)</span><textarea name="features_text" rows="4" placeholder="Breathable fabric&#10;Comfortable relaxed fit">{{ old('features_text', implode("\n", $product->features ?? [])) }}</textarea></label>
        </section>

        <section class="admin-card"><h2>Product images</h2><p class="admin-help">Upload images or use HTTPS image URLs. Uploaded files are stored in the app's public storage.</p>
            @if ($product->exists && $product->main_image)
                <div class="admin-main-image-preview"><img src="{{ $product->main_image_url }}" alt="Current main product image"><span>Current main image</span></div>
            @endif
            <div class="admin-form-grid">
                <label>Main image URL<input type="url" name="main_image" value="{{ old('main_image', Str::startsWith($product->main_image ?? '', 'storage/') ? '' : $product->main_image) }}" placeholder="https://…" maxlength="255"></label>
                <label>Upload main image<input type="file" name="main_image_upload" accept="image/*"><span>JPEG, PNG, WebP or GIF; up to 5 MB.</span></label>
            </div>
            @error('main_image_upload')<span class="field-error">{{ $message }}</span>@enderror
            @if ($product->exists && $product->images->where('is_primary', false)->isNotEmpty())
                <div class="admin-gallery-grid"><h3>Current gallery</h3>@foreach ($product->images->where('is_primary', false) as $image)<label class="admin-gallery-item"><img src="{{ $image->image_url }}" alt="{{ $image->alt_text ?? $product->name }}"><span><input type="checkbox" name="remove_image_ids[]" value="{{ $image->id }}" @checked(in_array($image->id, old('remove_image_ids', [])))> Remove</span></label>@endforeach</div>
            @endif
            <label class="admin-full-field">Add gallery image URLs <span>(one URL per line)</span><textarea name="gallery_urls" rows="3" placeholder="https://example.com/product-detail.jpg">{{ old('gallery_urls') }}</textarea></label>
            <label class="admin-full-field">Upload gallery images<input type="file" name="gallery_uploads[]" accept="image/*" multiple><span>Choose up to 10 image files, 5 MB each.</span></label>
        </section>

        <section class="admin-card"><div class="admin-section-heading"><div><h2>Variants and sizes</h2><p class="admin-help">Add sizes, colors, or other options. Each option can have its own stock and price adjustment.</p></div><button class="text-button" id="add-product-variant" type="button">+ Add option</button></div>
            <div class="admin-variant-list" id="admin-variant-list">
                @foreach ($variantRows as $index => $variant)
                    <div class="admin-variant-row">
                        <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant['id'] ?? '' }}">
                        <label>Option type<input name="variants[{{ $index }}][name]" value="{{ $variant['name'] ?? 'Size' }}" placeholder="Size"></label>
                        <label>Value<input name="variants[{{ $index }}][value]" value="{{ $variant['value'] ?? '' }}" placeholder="M, L, Blue…"></label>
                        <label>SKU<input name="variants[{{ $index }}][sku]" value="{{ $variant['sku'] ?? '' }}"></label>
                        <label>Stock<input name="variants[{{ $index }}][stock_quantity]" type="number" min="0" value="{{ $variant['stock_quantity'] ?? 0 }}"></label>
                        <label>Price adjustment<input name="variants[{{ $index }}][price_adjustment]" type="number" step="0.01" value="{{ $variant['price_adjustment'] ?? 0 }}"></label>
                        <label class="admin-checkbox-field"><input type="checkbox" name="variants[{{ $index }}][is_active]" value="1" @checked($variant['is_active'] ?? true)> Active</label>
                        <button type="button" class="remove-variant-row" aria-label="Remove option">&times;</button>
                    </div>
                @endforeach
            </div>
        </section>

        <div class="admin-form-actions"><a class="text-button" href="{{ route('admin.products.index') }}">Cancel</a><button class="button button-dark" type="submit">{{ $product->exists ? 'Save changes' : 'Create product' }}</button></div>
    </form>
</main>
<template id="admin-variant-template"><div class="admin-variant-row"><input type="hidden" data-field="id"><label>Option type<input data-field="name" value="Size" placeholder="Size"></label><label>Value<input data-field="value" placeholder="M, L, Blue…"></label><label>SKU<input data-field="sku"></label><label>Stock<input data-field="stock_quantity" type="number" min="0" value="0"></label><label>Price adjustment<input data-field="price_adjustment" type="number" step="0.01" value="0"></label><label class="admin-checkbox-field"><input data-field="is_active" type="checkbox" value="1" checked> Active</label><button type="button" class="remove-variant-row" aria-label="Remove option">&times;</button></div></template>
@push('scripts')<script src="{{ asset('js/admin-products.js') }}" defer></script>@endpush
@endsection
