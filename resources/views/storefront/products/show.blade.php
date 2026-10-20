@extends('layouts.storefront')

@section('title', $product->name.' — Usama')

@section('content')
<main class="product-page">
    <nav class="breadcrumbs" aria-label="Breadcrumb"><a href="{{ route('storefront.home') }}">Home</a><span>›</span><span>{{ $product->category }}</span><span>›</span><span>{{ $product->name }}</span></nav>

    @if ($confirmation)
        <section class="order-confirmation" role="status"><span class="confirmation-icon">✓</span><div><span class="eyebrow">ORDER RECEIVED</span><h2>Thank you, {{ $confirmation['customer_name'] }}.</h2><p>Your order <strong>{{ $confirmation['order_number'] }}</strong> is in. We’ll call {{ $confirmation['phone'] }} to confirm delivery. Total due on delivery: <strong>&#2547;{{ number_format($confirmation['total']) }}</strong>.</p></div><a class="text-link" href="{{ route('storefront.home') }}#shop">Browse more pieces <span>↗</span></a></section>
    @endif

    <section class="product-intro">
        <div class="product-gallery">
            @php($galleryImages = $product->images->isNotEmpty() ? $product->images : collect([(object) ['image_path' => $product->main_image, 'alt_text' => $product->name]]))
            <div class="product-main-image"><img id="main-product-image" src="{{ $product->main_image }}" alt="{{ $product->name }}" fetchpriority="high"><span class="gallery-index">01 / {{ str_pad((string) $galleryImages->count(), 2, '0', STR_PAD_LEFT) }}</span></div>
            @if ($galleryImages->count() > 1)
                <div class="product-thumbnails" aria-label="Product images">
                    @foreach ($galleryImages as $index => $image)
                        <button class="product-thumbnail{{ $index === 0 ? ' selected' : '' }}" type="button" data-gallery-image="{{ $image->image_path }}" data-gallery-alt="{{ $image->alt_text ?? $product->name }}" aria-label="View image {{ $index + 1 }}"><img src="{{ $image->image_path }}" alt="" loading="lazy"></button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-intro-copy">
            <span class="eyebrow">{{ $product->category }} · THE EVERYDAY EDIT</span>
            <h1>{{ $product->name }}</h1>
            <p class="product-promo">A thoughtful favorite, made for the way you live.</p>
            <div class="detail-price" data-price-base="{{ $product->currentPrice() }}" data-price-regular="{{ $product->regular_price }}"><strong id="product-current-price">&#2547;{{ number_format($product->currentPrice()) }}</strong>@if ($product->discount_price)<del>&#2547;{{ number_format($product->regular_price) }}</del><span class="discount-tag" id="detail-discount">−{{ $product->discountPercent() }}%</span>@endif</div>
            <p class="product-short-description">{{ $product->short_description }}</p>
            <ul class="benefit-list"><li><span>✓</span> {{ $product->quality_note }}</li><li><span>✓</span> Cash on delivery, with clear shipping upfront</li><li><span>✓</span> Easy 7-day size exchange</li></ul>
            <div class="intro-specs"><div><span>MATERIAL</span><strong>{{ $product->material }}</strong></div><div><span>AVAILABILITY</span><strong>{{ $product->stock_quantity > 0 ? 'Ready to order' : 'Currently sold out' }}</strong></div></div>
            <a class="button button-dark jump-to-order" href="#billing">Choose options & order <span>↓</span></a>
        </div>
    </section>

    <section class="product-information">
        <div class="information-copy"><span class="eyebrow">A LITTLE MORE ABOUT IT</span><h2>Made for your <em>everyday.</em></h2><p>{{ $product->description }}</p><div class="info-note"><span>QUALITY, THOUGHT THROUGH</span><p>{{ $product->quality_note }} Each piece is checked before it leaves us.</p></div></div>
        <div class="information-specs"><h3>Product details</h3><dl><div><dt>Material</dt><dd>{{ $product->material }}</dd></div><div><dt>Colors</dt><dd>{{ $product->category === 'ACCESSORIES' ? 'See available options' : 'Multiple colors available' }}</dd></div><div><dt>Size</dt><dd>See currently available sizes below</dd></div><div><dt>Delivery</dt><dd>Dhaka and nationwide delivery. Rates shown in your order summary.</dd></div><div><dt>Exchange</dt><dd>Contact us within 7 days for an eligible size exchange.</dd></div></dl></div>
    </section>

    <section class="billing-section" id="billing">
        <div class="billing-heading"><span class="eyebrow">ONE PAGE, ALL SET</span><h2>Complete your <em>order.</em></h2><p>Choose your option, add delivery details, and review your total before placing the order.</p></div>
        <div class="billing-layout">
            <form class="order-form" method="POST" action="{{ route('products.orders.store', $product) }}" id="order-form">
                @csrf
                <fieldset class="form-section"><legend><span>01</span> Product option</legend>
                    <label for="product-variant">Available {{ $product->variants->first()?->name ?? 'option' }}</label>
                    <select id="product-variant" name="product_variant_id" required @disabled($product->stock_quantity < 1)>
                        <option value="">Choose an option</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-adjustment="{{ $variant->price_adjustment }}" data-stock="{{ $variant->stock_quantity }}" @selected(old('product_variant_id') == $variant->id) @disabled($variant->stock_quantity < 1)>{{ $variant->name }}: {{ $variant->value }}@if ($variant->price_adjustment > 0) (+&#2547;{{ number_format($variant->price_adjustment) }})@endif @if ($variant->stock_quantity < 1) — Sold out @endif</option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<span class="field-error">{{ $message }}</span>@enderror
                    <p class="stock-hint" id="stock-hint">Select an available option to see stock.</p>
                    <label for="quantity">Quantity</label>
                    <div class="quantity-input"><button type="button" data-quantity-step="-1" aria-label="Decrease quantity">−</button><input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" required><button type="button" data-quantity-step="1" aria-label="Increase quantity">+</button></div>
                    @error('quantity')<span class="field-error">{{ $message }}</span>@enderror
                </fieldset>

                <fieldset class="form-section"><legend><span>02</span> Billing details</legend>
                    <label for="customer-name">Full name</label><input id="customer-name" name="customer_name" type="text" autocomplete="name" value="{{ old('customer_name') }}" placeholder="Your name" required maxlength="120">@error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="phone">Mobile number</label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" pattern="(?:\+?88)?01[3-9]\d{8}" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="address">Full delivery address</label><textarea id="address" name="address" autocomplete="street-address" rows="3" placeholder="House, road, area" required maxlength="1000">{{ old('address') }}</textarea>@error('address')<span class="field-error">{{ $message }}</span>@enderror
                    <div class="form-two-columns"><div><label for="district">District</label><input id="district" name="district" value="{{ old('district') }}" placeholder="e.g. Dhaka" required maxlength="100"></div><div><label for="area">Area <span>(optional)</span></label><input id="area" name="area" value="{{ old('area') }}" placeholder="e.g. Mirpur" maxlength="120"></div></div>@error('district')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="customer-notes">Order notes <span>(optional)</span></label><textarea id="customer-notes" name="customer_notes" rows="2" placeholder="Anything we should know?" maxlength="1000">{{ old('customer_notes') }}</textarea>
                </fieldset>

                <fieldset class="form-section shipping-section"><legend><span>03</span> Delivery & payment</legend>
                    <p class="form-help">Choose the delivery area that fits your address.</p>
                    <div class="shipping-options">
                        @foreach ($shippingOptions as $index => $option)
                            <label class="shipping-option"><input type="radio" name="shipping_option_id" value="{{ $option->id }}" data-charge="{{ $option->charge }}" @checked(old('shipping_option_id') == $option->id || (!old('shipping_option_id') && $index === 0)) required><span class="shipping-choice"><strong>{{ $option->name }}</strong><small>{{ $loop->first ? 'Usually arrives in 1–2 days' : 'Usually arrives in 2–4 days' }}</small></span><b>&#2547;{{ number_format($option->charge) }}</b></label>
                        @endforeach
                    </div>
                    @error('shipping_option_id')<span class="field-error">{{ $message }}</span>@enderror
                    <label class="payment-choice"><input type="radio" name="payment_method" value="cod" checked required><span><strong>Cash on Delivery</strong><small>Pay in cash when your order arrives.</small></span><span class="cod-mark">COD</span></label>
                </fieldset>

                @if ($errors->any())<div class="form-error-summary" role="alert">Please check the highlighted order details and try again.</div>@endif
                <button class="button button-dark place-order-button" type="submit" @disabled($product->stock_quantity < 1)>Place Order — <span id="place-order-total">&#2547;{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</span></button>
                <p class="secure-note">No online payment needed. We’ll contact you to confirm the order.</p>
            </form>

            <aside class="order-summary" aria-live="polite" data-base-price="{{ $product->currentPrice() }}" data-regular-price="{{ $product->regular_price }}">
                <span class="eyebrow">YOUR ORDER</span><h3>Order summary</h3>
                <div class="summary-product"><img src="{{ $product->main_image }}" alt="" loading="lazy"><div><strong>{{ $product->name }}</strong><span id="summary-variant">Choose a size or option</span><span id="summary-quantity">Quantity: {{ old('quantity', 1) }}</span></div><b id="summary-subtotal">&#2547;{{ number_format($product->currentPrice()) }}</b></div>
                @if ($product->discount_price)<div class="summary-row discount-row"><span>You save</span><strong id="summary-discount">−&#2547;{{ number_format($product->regular_price - $product->currentPrice()) }}</strong></div>@endif
                <div class="summary-row"><span>Subtotal</span><strong id="summary-subtotal-line">&#2547;{{ number_format($product->currentPrice()) }}</strong></div>
                <div class="summary-row"><span id="summary-shipping-name">{{ $shippingOptions->first()?->name ?? 'Delivery' }}</span><strong id="summary-shipping">&#2547;{{ number_format($shippingOptions->first()?->charge ?? 0) }}</strong></div>
                <div class="summary-total"><span>Total due on delivery</span><strong id="summary-total">&#2547;{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</strong></div>
                <p class="summary-footnote">Final pricing is checked again when you place the order.</p>
            </aside>
        </div>
    </section>
</main>
@endsection
