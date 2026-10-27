<section class="home-featured-product" id="blueberry-order" aria-labelledby="blueberry-heading">
    <div class="home-featured-heading">
        <span class="eyebrow">A SWEETCRUST FAVORITE</span>
        <h2>Blueberry <em>Cheesecake</em></h2>
        <p>All the product details and checkout in one place.</p>
    </div>

    @if ($confirmation)
        <dialog class="order-success-dialog" id="order-success-dialog" aria-labelledby="order-success-title">
            <section class="order-confirmation order-confirmation-popup" role="status">
                <span class="confirmation-icon">&#10003;</span>
                <button class="success-close" id="close-order-success" type="button" aria-label="Close message">&times;</button>
                <div>
                    <span class="eyebrow">ORDER RECEIVED</span>
                    <h2 id="order-success-title">Thank you, {{ $confirmation['customer_name'] }}.</h2>
                    <p>Your order number is <strong>{{ $confirmation['order_number'] }}</strong>. We’ll call {{ $confirmation['phone'] }} to confirm delivery. Pay when it arrives: <strong>&#2547;{{ number_format($confirmation['total']) }}</strong>.</p>
                    <!-- <a class="text-link" href="{{ route('storefront.home') }}#shop">Browse more cakes &amp; desserts <span>&#8599;</span></a> -->
                </div>
            </section>
        </dialog>
    @endif

    @if ($featuredProduct)
        @php($featuredName = $featuredProduct->name)
        @php($featuredImages = $featuredProduct->images->isNotEmpty() ? $featuredProduct->images : collect())
        @php($shippingTranslations = ['inside-dhaka' => 'Inside Dhaka', 'outside-dhaka' => 'Outside Dhaka'])
        <div class="home-featured-layout">
            <div class="home-product-details">
                <div class="home-product-gallery">
                    <div class="home-product-main-image">
                        <img id="main-product-image" src="{{ $featuredProduct->main_image_url }}" alt="{{ $featuredName }}" fetchpriority="high">
                        <span class="gallery-index">01 / {{ str_pad((string) max(1, $featuredImages->count()), 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    @if ($featuredImages->count() > 1)
                        <div class="product-thumbnails" aria-label="Blueberry Cheesecake photos">
                            @foreach ($featuredImages as $imageIndex => $image)
                                <button class="product-thumbnail{{ $imageIndex === 0 ? ' selected' : '' }}" type="button" data-gallery-image="{{ $image->image_url }}" data-gallery-alt="{{ $image->alt_text ?? $featuredName }}" aria-label="View photo {{ $imageIndex + 1 }}"><img src="{{ $image->image_url }}" alt="" loading="lazy"></button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="home-product-copy">
                    <span class="eyebrow">CAKES &middot; BAKED FRESH</span>
                    <h3 id="blueberry-heading">{{ $featuredName }}</h3>
                    <div class="detail-price" data-price-base="{{ $featuredProduct->currentPrice() }}" data-price-regular="{{ $featuredProduct->regular_price }}">
                        <strong id="product-current-price">&#2547;{{ number_format($featuredProduct->currentPrice()) }}</strong>
                        @if ($featuredProduct->discount_price)<del>&#2547;{{ number_format($featuredProduct->regular_price) }}</del><span class="discount-tag" id="detail-discount">{{ $featuredProduct->discountPercent() }}% off</span>@endif
                    </div>
                    <p class="home-product-short">{{ $featuredProduct->short_description }}</p>
                    <div class="home-product-description">
                        <h4>Product details</h4>
                        <p>{{ $featuredProduct->description }}</p>
                    </div>
                    <dl class="home-product-specs">
                        <div><dt>Preparation</dt><dd>{{ $featuredProduct->material }}</dd></div>
                        <div><dt>Freshness</dt><dd>{{ $featuredProduct->quality_note }}</dd></div>
                        <div><dt>Availability</dt><dd>{{ $featuredProduct->stock_quantity > 0 ? 'Ready to order' : 'Currently unavailable' }}</dd></div>
                    </dl>
                    @if ($featuredProduct->features)
                        <ul class="benefit-list">@foreach ($featuredProduct->features as $feature)<li><span>&#10003;</span> {{ $feature }}</li>@endforeach</ul>
                    @endif
                </div>
            </div>

            <div class="home-checkout-panel">
                <div class="home-checkout-heading"><span class="eyebrow">QUICK, SECURE CHECKOUT</span><h3>Order your cheesecake</h3><p>Choose a size, enter your delivery details, and see your total before placing the order.</p></div>
                <form class="order-form home-order-form" method="POST" action="{{ route('products.orders.store', $featuredProduct) }}" id="order-form">
                    @csrf
                    <input type="hidden" name="return_to" value="home">

                    <fieldset class="form-section">
                        <legend><span>01</span> Choose your cheesecake</legend>
                        <label for="product-variant">Choose a size</label>
                        <select id="product-variant" name="product_variant_id" required @disabled($featuredProduct->stock_quantity < 1 || $featuredProduct->variants->isEmpty())>
                            <option value="">Select a size</option>
                            @foreach ($featuredProduct->variants as $variant)
                                <option value="{{ $variant->id }}" data-adjustment="{{ $variant->price_adjustment }}" data-stock="{{ $variant->stock_quantity }}" @selected(old('product_variant_id') == $variant->id) @disabled($variant->stock_quantity < 1)>{{ $variant->value }}@if ($variant->price_adjustment > 0) (+&#2547;{{ number_format($variant->price_adjustment) }})@endif @if ($variant->stock_quantity < 1) - Out of stock @endif</option>
                            @endforeach
                        </select>
                        @error('product_variant_id')<span class="field-error">{{ $message }}</span>@enderror
                        <p class="stock-hint" id="stock-hint">Select a size to check availability.</p>
                        <label for="quantity">Quantity</label>
                        <div class="quantity-input"><button type="button" data-quantity-step="-1" aria-label="Decrease quantity">-</button><input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" required><button type="button" data-quantity-step="1" aria-label="Increase quantity">+</button></div>
                        @error('quantity')<span class="field-error">{{ $message }}</span>@enderror
                    </fieldset>

                    <fieldset class="form-section">
                        <legend><span>02</span> Delivery details</legend>
                        <label for="customer-name">Customer name</label><input id="customer-name" name="customer_name" type="text" autocomplete="name" value="{{ old('customer_name') }}" placeholder="Enter your name" required maxlength="120">@error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                        <label for="phone">Phone number</label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" pattern="(?:\+?88)?01[3-9]\d{8}" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror
                        <label for="address">Delivery address</label><textarea id="address" name="address" autocomplete="street-address" rows="2" placeholder="House, street, and area" required maxlength="1000">{{ old('address') }}</textarea>@error('address')<span class="field-error">{{ $message }}</span>@enderror
                        <div class="form-two-columns">
                            <div><label for="district">District</label><select id="district" name="district_id" required><option value="">Choose district</option>@foreach ($districts as $district)<option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>@endforeach</select>@error('district_id')<span class="field-error">{{ $message }}</span>@enderror</div>
                            <div><label for="area">Area</label><select id="area" name="area_id" required disabled><option value="">Choose district first</option>@foreach ($districts as $district)@foreach ($district->areas as $area)<option value="{{ $area->id }}" data-district="{{ $district->id }}" @selected(old('area_id') == $area->id)>{{ $area->name }}</option>@endforeach @endforeach</select>@error('area_id')<span class="field-error">{{ $message }}</span>@enderror</div>
                        </div>
                        <label for="customer-notes">Order note <span>(Optional)</span></label><textarea id="customer-notes" name="customer_notes" rows="2" placeholder="Add a delivery note" maxlength="1000">{{ old('customer_notes') }}</textarea>
                    </fieldset>

                    <fieldset class="form-section shipping-section">
                        <legend><span>03</span> Delivery &amp; payment</legend>
                        <div class="shipping-options">
                            @foreach ($shippingOptions as $shippingIndex => $option)
                                @php($shippingLabel = $shippingTranslations[$option->key] ?? $option->name)
                                <label class="shipping-option"><input type="radio" name="shipping_option_id" value="{{ $option->id }}" data-charge="{{ $option->charge }}" @checked(old('shipping_option_id') == $option->id || (!old('shipping_option_id') && $shippingIndex === 0)) required><span class="shipping-choice"><strong>{{ $shippingLabel }}</strong><small>{{ $shippingIndex === 0 ? 'Usually arrives in 1–2 days' : 'Usually arrives in 2–4 days' }}</small></span><b>&#2547;{{ number_format($option->charge) }}</b></label>
                            @endforeach
                        </div>
                        @error('shipping_option_id')<span class="field-error">{{ $message }}</span>@enderror
                        <label class="payment-choice"><input type="radio" name="payment_method" value="cod" checked required><span><strong>Cash on delivery</strong><small>Pay when your order arrives.</small></span><span class="cod-mark">COD</span></label>
                    </fieldset>

                    @if ($errors->any())<div class="form-error-summary" role="alert">Please review your order details. {{ $errors->first() }}</div>@endif

                    <aside class="order-summary" aria-live="polite" data-base-price="{{ $featuredProduct->currentPrice() }}" data-regular-price="{{ $featuredProduct->regular_price }}">
                        <span class="eyebrow">YOUR ORDER</span><h3>Order summary</h3>
                        <div class="summary-product"><img src="{{ $featuredProduct->main_image_url }}" alt="" loading="lazy"><div><strong>{{ $featuredName }}</strong><span id="summary-variant">Choose a size</span><span id="summary-quantity">Quantity: {{ old('quantity', 1) }}</span></div><b id="summary-subtotal">&#2547;{{ number_format($featuredProduct->currentPrice()) }}</b></div>
                        @if ($featuredProduct->discount_price)<div class="summary-row discount-row"><span>You save</span><strong id="summary-discount">- &#2547;{{ number_format($featuredProduct->regular_price - $featuredProduct->currentPrice()) }}</strong></div>@endif
                        <div class="summary-row"><span>Item total</span><strong id="summary-subtotal-line">&#2547;{{ number_format($featuredProduct->currentPrice()) }}</strong></div>
                        <div class="summary-row"><span id="summary-shipping-name">{{ $shippingTranslations[$shippingOptions->first()?->key] ?? ($shippingOptions->first()?->name ?? 'Delivery') }}</span><strong id="summary-shipping">&#2547;{{ number_format($shippingOptions->first()?->charge ?? 0) }}</strong></div>
                        <div class="summary-total"><span>Total due on delivery</span><strong id="summary-total">&#2547;{{ number_format($featuredProduct->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</strong></div>
                        <p class="summary-footnote">We’ll confirm your order and delivery details by phone.</p>
                    </aside>
                    <button class="button button-dark place-order-button" type="submit" id="home-place-order" @disabled($featuredProduct->stock_quantity < 1 || $featuredProduct->variants->isEmpty())>Place Order <span id="place-order-total">&#2547;{{ number_format($featuredProduct->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }}</span></button>
                    <p class="secure-note">No online payment needed. Pay by cash on delivery.</p>
                </form>
            </div>
        </div>
    @else
        <p class="home-product-unavailable">Blueberry Cheesecake is temporarily unavailable. Please check back soon.</p>
    @endif
</section>
