@extends('layouts.storefront')

@php
    $productName = $product->name;
    $categoryName = $product->category === 'CAKES' ? 'Cakes' : 'Desserts';
    $shippingTranslations = ['inside-dhaka' => 'Inside Dhaka', 'outside-dhaka' => 'Outside Dhaka'];
@endphp

@section('title', $productName.' - SweetCrust')

@section('content')
<main class="product-page">
    <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('storefront.home') }}">Home</a><span>›</span><a href="{{ route('storefront.home') }}#shop">{{ $categoryName }}</a><span>›</span><span>{{ $productName }}</span></nav>

    @if ($confirmation)
        <dialog class="order-success-dialog" id="order-success-dialog" aria-labelledby="order-success-title">
            <section class="order-confirmation order-confirmation-popup" role="status">
                <span class="confirmation-icon">&#10003;</span>
                <button class="success-close" id="close-order-success" type="button" aria-label="Close message">&times;</button>
                <div>
                    <span class="eyebrow">ORDER RECEIVED</span>
                    <h2 id="order-success-title">Thank you, {{ $confirmation['customer_name'] }}.</h2>
                    <p>Your order number is <strong>{{ $confirmation['order_number'] }}</strong>. We’ll call {{ $confirmation['phone'] }} to confirm delivery. Pay when it arrives: <strong>৳{{ number_format($confirmation['total']) }}</strong>.</p>
                    <a class="text-link" href="{{ route('storefront.home') }}#shop">Explore more treats <span>&#8599;</span></a>
                </div>
            </section>
        </dialog>
    @endif

    <section class="product-intro">
        <div class="product-gallery">
            @php($galleryImages = $product->images->isNotEmpty() ? $product->images : collect([(object) ['image_path' => $product->main_image, 'alt_text' => $productName]]) )
            <div class="product-main-image"><img id="main-product-image" src="{{ $product->main_image_url }}" alt="{{ $productName }}" fetchpriority="high"><span class="gallery-index">01 / {{ str_pad((string) $galleryImages->count(), 2, '0', STR_PAD_LEFT) }}</span></div>
            @if ($galleryImages->count() > 1)
                <div class="product-thumbnails" aria-label="Product photos">
                    @foreach ($galleryImages as $index => $image)
                        <button class="product-thumbnail{{ $index === 0 ? ' selected' : '' }}" type="button" data-gallery-image="{{ $image->image_url }}" data-gallery-alt="{{ $image->alt_text ?? $productName }}" aria-label="View photo {{ $index + 1 }}"><img src="{{ $image->image_url }}" alt="" loading="lazy"></button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-intro-copy">
            <span class="eyebrow">{{ $categoryName }} · THE SWEETCRUST EDIT</span>
            <h1>{{ $productName }}</h1>
            <p class="product-promo">A freshly prepared favorite for celebrations, sharing, or a little treat for yourself.</p>
            <div class="detail-price" data-price-base="{{ $product->currentPrice() }}" data-price-regular="{{ $product->regular_price }}"><strong id="product-current-price">৳{{ number_format($product->currentPrice()) }}</strong>@if ($product->discount_price)<del>৳{{ number_format($product->regular_price) }}</del><span class="discount-tag" id="detail-discount">{{ $product->discountPercent() }}% off</span>@endif</div>
            <p class="product-short-description">{{ $product->short_description }}</p>
            <ul class="benefit-list">
                @php($features = $product->features ?? [])
                @forelse ($features as $feature)
                    <li><span>✓</span> {{ $feature }}</li>
                @empty
                    <li><span>✓</span> {{ $product->quality_note }}</li><li><span>✓</span> Cash on delivery, with shipping shown upfront</li><li><span>✓</span> Packed carefully for delivery</li>
                @endforelse
            </ul>
            <div class="intro-specs"><div><span>Prepared</span><strong>{{ $product->material }}</strong></div><div><span>Availability</span><strong>{{ $product->stock_quantity > 0 ? 'Ready to order' : 'Currently out of stock' }}</strong></div></div>
            <a class="button button-dark jump-to-order" href="#billing">Choose options &amp; order <span>↓</span></a>
        </div>
    </section>

    <section class="product-information">
        <div class="information-copy"><span class="eyebrow">A LITTLE MORE ABOUT IT</span><h2>Made for a <em>sweet moment.</em></h2><p>{{ $product->description }}</p><div class="info-note"><span>Prepared with care</span><p>{{ $product->quality_note }} Your order is packed carefully before dispatch.</p></div></div>
        <div class="information-specs"><h3>Product details</h3><dl><div><dt>Category</dt><dd>{{ $categoryName }}</dd></div><div><dt>Preparation</dt><dd>{{ $product->material }}</dd></div><div><dt>Portion</dt><dd>Choose from the available sizes or box options below.</dd></div><div><dt>Delivery</dt><dd>Delivery across Bangladesh. Check the charge before placing your order.</dd></div><div><dt>Payment</dt><dd>Pay by cash when your order arrives.</dd></div></dl></div>
    </section>

    <section class="billing-section" id="billing">
        <div class="billing-heading"><span class="eyebrow">A SIMPLE, ONE-PAGE CHECKOUT</span><h2>Send something <em>sweet.</em></h2><p>Choose a size or box, add your delivery details, and see the full total before you order.</p></div>
        <div class="billing-layout">
            <form class="order-form" method="POST" action="{{ route('products.orders.store', $product) }}" id="order-form">
                @csrf
                <fieldset class="form-section"><legend><span>01</span> Choose your options</legend>
                    <label for="product-variant">{{ match($product->variants->first()?->name) { 'Size' => 'Choose a size', 'Color' => 'Choose a color', default => 'Choose an option' } }}</label>
                    <select id="product-variant" name="product_variant_id" required @disabled($product->stock_quantity < 1)>
                        <option value="">Select an option</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-adjustment="{{ $variant->price_adjustment }}" data-stock="{{ $variant->stock_quantity }}" @selected(old('product_variant_id') == $variant->id) @disabled($variant->stock_quantity < 1)>{{ $variant->name }}: {{ $variant->value }}@if ($variant->price_adjustment > 0) (+৳{{ number_format($variant->price_adjustment) }})@endif @if ($variant->stock_quantity < 1) - Out of stock @endif</option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<span class="field-error">{{ $message }}</span>@enderror
                    <p class="stock-hint" id="stock-hint">Choose a size or box to check availability.</p>
                    <label for="quantity">Quantity</label>
                    <div class="quantity-input"><button type="button" data-quantity-step="-1" aria-label="Decrease quantity">-</button><input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" required><button type="button" data-quantity-step="1" aria-label="Increase quantity">+</button></div>
                    @error('quantity')<span class="field-error">{{ $message }}</span>@enderror
                </fieldset>

                <fieldset class="form-section"><legend><span>02</span> Your details</legend>
                    <label for="customer-name">Full name</label><input id="customer-name" name="customer_name" type="text" autocomplete="name" value="{{ old('customer_name') }}" placeholder="Enter your name" required maxlength="120">@error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="phone">Mobile number</label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" pattern="(?:\+?88)?01[3-9]\d{8}" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="address">Full delivery address</label><textarea id="address" name="address" autocomplete="street-address" rows="3" placeholder="House, street, and area" required maxlength="1000">{{ old('address') }}</textarea>@error('address')<span class="field-error">{{ $message }}</span>@enderror
                    <div class="form-two-columns"><div><label for="district">District</label><select id="district" name="district_id" required><option value="">Choose a district</option>@foreach ($districts as $district)<option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>@endforeach</select>@error('district_id')<span class="field-error">{{ $message }}</span>@enderror</div><div><label for="area">Area</label><select id="area" name="area_id" required disabled><option value="">Choose a district first</option>@foreach ($districts as $district)@foreach ($district->areas as $area)<option value="{{ $area->id }}" data-district="{{ $district->id }}" @selected(old('area_id') == $area->id)>{{ $area->name }}</option>@endforeach @endforeach</select>@error('area_id')<span class="field-error">{{ $message }}</span>@enderror</div></div>
                    <label for="customer-notes">Anything we should know? <span>(Optional)</span></label><textarea id="customer-notes" name="customer_notes" rows="2" placeholder="Add a delivery note" maxlength="1000">{{ old('customer_notes') }}</textarea>
                </fieldset>

                <fieldset class="form-section shipping-section"><legend><span>03</span> Delivery &amp; payment</legend>
                    <p class="form-help">Choose the delivery area that matches your address.</p>
                    <div class="shipping-options">
                        @foreach ($shippingOptions as $index => $option)
                            @php($shippingLabel = $shippingTranslations[$option->key] ?? $option->name)
                            <label class="shipping-option"><input type="radio" name="shipping_option_id" value="{{ $option->id }}" data-charge="{{ $option->charge }}" @checked(old('shipping_option_id') == $option->id || (!old('shipping_option_id') && $index === 0)) required><span class="shipping-choice"><strong>{{ $shippingLabel }}</strong><small>{{ $loop->first ? 'Usually arrives in 1–2 days' : 'Usually arrives in 2–4 days' }}</small></span><b>৳{{ number_format($option->charge) }}</b></label>
                        @endforeach
                    </div>
                    @error('shipping_option_id')<span class="field-error">{{ $message }}</span>@enderror
                    <label class="payment-choice"><input type="radio" name="payment_method" value="cod" checked required><span><strong>Cash on delivery</strong><small>Pay in cash when your order arrives.</small></span><span class="cod-mark">COD</span></label>
                </fieldset>

                @if ($errors->any())<div class="form-error-summary" role="alert">Please review your details and try again.</div>@endif
                <button class="button button-dark place-order-button" type="submit" @disabled($product->stock_quantity < 1)>Place your order — <span id="place-order-total">৳{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</span></button>
                <p class="secure-note">No online payment needed. We’ll contact you to confirm your order.</p>
            </form>

            <aside class="order-summary" aria-live="polite" data-base-price="{{ $product->currentPrice() }}" data-regular-price="{{ $product->regular_price }}">
                <span class="eyebrow">YOUR ORDER</span><h3>Order summary</h3>
                <div class="summary-product"><img src="{{ $product->main_image_url }}" alt="" loading="lazy"><div><strong>{{ $productName }}</strong><span id="summary-variant">Choose a size or option</span><span id="summary-quantity">Quantity: {{ old('quantity', 1) }}</span></div><b id="summary-subtotal">৳{{ number_format($product->currentPrice()) }}</b></div>
                @if ($product->discount_price)<div class="summary-row discount-row"><span>You save</span><strong id="summary-discount">− ৳{{ number_format($product->regular_price - $product->currentPrice()) }}</strong></div>@endif
                <div class="summary-row"><span>Item total</span><strong id="summary-subtotal-line">৳{{ number_format($product->currentPrice()) }}</strong></div>
                <div class="summary-row"><span id="summary-shipping-name">{{ isset($shippingTranslations[$shippingOptions->first()?->key]) ? $shippingTranslations[$shippingOptions->first()->key] : ($shippingOptions->first()?->name ?? 'Delivery') }}</span><strong id="summary-shipping">৳{{ number_format($shippingOptions->first()?->charge ?? 0) }}</strong></div>
                <div class="summary-total"><span>Total due on delivery</span><strong id="summary-total">৳{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</strong></div>
                <p class="summary-footnote">We’ll confirm the final amount when we confirm your order.</p>
            </aside>
        </div>
    </section>
</main>
@endsection
