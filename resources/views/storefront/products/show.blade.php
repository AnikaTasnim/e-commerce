@extends('layouts.storefront')

@php
    $catalogEntry = trans('catalog.'.$product->slug, [], 'bn');
    $copy = is_array($catalogEntry) ? $catalogEntry : [];
    $productName = $copy['name'] ?? $product->name;
    $categoryName = ['WOMEN' => 'নারীদের', 'MEN' => 'পুরুষদের', 'ACCESSORIES' => 'অনুষঙ্গ'][$product->category] ?? $product->category;
    $districtTranslations = trans('locations.districts', [], 'bn');
    $areaTranslations = trans('locations.areas', [], 'bn');
    $shippingTranslations = ['inside-dhaka' => 'ঢাকার ভেতরে', 'outside-dhaka' => 'ঢাকার বাইরে'];
@endphp

@section('title', $productName.' - বিএসআর')

@section('content')
<main class="product-page">
    <nav class="breadcrumbs" aria-label="আপনি এখানে"><a href="{{ route('storefront.home') }}">হোম</a><span>›</span><span>{{ $categoryName }}</span><span>›</span><span>{{ $productName }}</span></nav>

    @if ($confirmation)
        <dialog class="order-success-dialog" id="order-success-dialog" aria-labelledby="order-success-title">
            <section class="order-confirmation order-confirmation-popup" role="status">
                <span class="confirmation-icon">&#10003;</span>
                <button class="success-close" id="close-order-success" type="button" aria-label="বার্তা বন্ধ করুন">&times;</button>
                <div>
                    <span class="eyebrow">অর্ডার গ্রহণ করা হয়েছে</span>
                    <h2 id="order-success-title">ধন্যবাদ, {{ $confirmation['customer_name'] }}।</h2>
                    <p>আপনার অর্ডার নম্বর <strong>{{ $confirmation['order_number'] }}</strong>। ডেলিভারি নিশ্চিত করতে আমরা {{ $confirmation['phone'] }} নম্বরে যোগাযোগ করব। পণ্য হাতে পেয়ে পরিশোধ করবেন: <strong>{{ number_format($confirmation['total']) }} টাকা</strong>।</p>
                    <a class="text-link" href="{{ route('storefront.home') }}#shop">আরও পণ্য দেখুন <span>&#8599;</span></a>
                </div>
            </section>
        </dialog>
    @endif

    <section class="product-intro">
        <div class="product-gallery">
            @php($galleryImages = $product->images->isNotEmpty() ? $product->images : collect([(object) ['image_path' => $product->main_image, 'alt_text' => $productName]]) )
            <div class="product-main-image"><img id="main-product-image" src="{{ $product->main_image_url }}" alt="{{ $productName }}" fetchpriority="high"><span class="gallery-index">০১ / {{ str_pad((string) $galleryImages->count(), 2, '0', STR_PAD_LEFT) }}</span></div>
            @if ($galleryImages->count() > 1)
                <div class="product-thumbnails" aria-label="পণ্যের ছবি">
                    @foreach ($galleryImages as $index => $image)
                        <button class="product-thumbnail{{ $index === 0 ? ' selected' : '' }}" type="button" data-gallery-image="{{ $image->image_url }}" data-gallery-alt="{{ $image->alt_text ?? $productName }}" aria-label="ছবি {{ $index + 1 }} দেখুন"><img src="{{ $image->image_url }}" alt="" loading="lazy"></button>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="product-intro-copy">
            <span class="eyebrow">{{ $categoryName }} · প্রতিদিনের সংগ্রহ</span>
            <h1>{{ $productName }}</h1>
            <p class="product-promo">আপনার প্রতিদিনের জন্য যত্নে বেছে নেওয়া একটি পছন্দ।</p>
            <div class="detail-price" data-price-base="{{ $product->currentPrice() }}" data-price-regular="{{ $product->regular_price }}"><strong id="product-current-price">{{ number_format($product->currentPrice()) }} টাকা</strong>@if ($product->discount_price)<del>{{ number_format($product->regular_price) }} টাকা</del><span class="discount-tag" id="detail-discount">{{ $product->discountPercent() }}% ছাড়</span>@endif</div>
            <p class="product-short-description">{{ $copy['short'] ?? $product->short_description }}</p>
            <ul class="benefit-list">
                @php($features = $copy['features'] ?? ($product->features ?? []))
                @forelse ($features as $feature)
                    <li><span>✓</span> {{ $feature }}</li>
                @empty
                    <li><span>✓</span> {{ $copy['quality'] ?? $product->quality_note }}</li><li><span>✓</span> ক্যাশ অন ডেলিভারি, ডেলিভারি চার্জ আগে থেকেই জানুন</li><li><span>✓</span> ৭ দিনের মধ্যে সাইজ বদলের সুবিধা</li>
                @endforelse
            </ul>
            <div class="intro-specs"><div><span>উপাদান</span><strong>{{ $copy['material'] ?? $product->material }}</strong></div><div><span>পণ্যের অবস্থা</span><strong>{{ $product->stock_quantity > 0 ? 'অর্ডার করতে পারবেন' : 'এই মুহূর্তে স্টকে নেই' }}</strong></div></div>
            <a class="button button-dark jump-to-order" href="#billing">সাইজ বেছে অর্ডার করুন <span>↓</span></a>
        </div>
    </section>

    <section class="product-information">
        <div class="information-copy"><span class="eyebrow">পণ্যটি সম্পর্কে</span><h2>আপনার প্রতিদিনের <em>সঙ্গী।</em></h2><p>{{ $copy['description'] ?? $product->description }}</p><div class="info-note"><span>মানের যত্ন</span><p>{{ $copy['quality'] ?? $product->quality_note }} পাঠানোর আগে প্রতিটি পণ্য পরীক্ষা করা হয়।</p></div></div>
        <div class="information-specs"><h3>পণ্যের বিবরণ</h3><dl><div><dt>উপাদান</dt><dd>{{ $copy['material'] ?? $product->material }}</dd></div><div><dt>রং</dt><dd>{{ $copy['colors'] ?? 'পণ্যের ছবিতে দেখুন' }}</dd></div><div><dt>সাইজ</dt><dd>নিচে পাওয়া যাচ্ছে এমন সাইজ বেছে নিন</dd></div><div><dt>ডেলিভারি</dt><dd>ঢাকা ও সারা দেশে ডেলিভারি। অর্ডারের আগে চার্জ দেখে নিন।</dd></div><div><dt>পণ্য বদল</dt><dd>সাইজ বদলাতে ৭ দিনের মধ্যে আমাদের সঙ্গে যোগাযোগ করুন।</dd></div></dl></div>
    </section>

    <section class="billing-section" id="billing">
        <div class="billing-heading"><span class="eyebrow">এক পেজেই অর্ডার সম্পন্ন করুন</span><h2>আপনার অর্ডার <em>দিন।</em></h2><p>সাইজ বেছে ঠিকানা দিন, অর্ডারের আগে মোট মূল্য দেখে নিন।</p></div>
        <div class="billing-layout">
            <form class="order-form" method="POST" action="{{ route('products.orders.store', $product) }}" id="order-form">
                @csrf
                <fieldset class="form-section"><legend><span>০১</span> পণ্যের অপশন</legend>
                    <label for="product-variant">{{ match($product->variants->first()?->name) { 'Size' => 'সাইজ বেছে নিন', 'Color' => 'রং বেছে নিন', default => 'পছন্দের অপশন বেছে নিন' } }}</label>
                    <select id="product-variant" name="product_variant_id" required @disabled($product->stock_quantity < 1)>
                        <option value="">একটি অপশন বেছে নিন</option>
                        @foreach ($product->variants as $variant)
                            <option value="{{ $variant->id }}" data-adjustment="{{ $variant->price_adjustment }}" data-stock="{{ $variant->stock_quantity }}" @selected(old('product_variant_id') == $variant->id) @disabled($variant->stock_quantity < 1)>{{ $variant->name === 'Size' ? 'সাইজ' : ($variant->name === 'Color' ? 'রং' : $variant->name) }}: {{ $variant->value }}@if ($variant->price_adjustment > 0) (+{{ number_format($variant->price_adjustment) }} টাকা)@endif @if ($variant->stock_quantity < 1) - স্টকে নেই @endif</option>
                        @endforeach
                    </select>
                    @error('product_variant_id')<span class="field-error">{{ $message }}</span>@enderror
                    <p class="stock-hint" id="stock-hint">স্টক দেখতে একটি অপশন বেছে নিন।</p>
                    <label for="quantity">পরিমাণ</label>
                    <div class="quantity-input"><button type="button" data-quantity-step="-1" aria-label="পরিমাণ কমান">-</button><input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" required><button type="button" data-quantity-step="1" aria-label="পরিমাণ বাড়ান">+</button></div>
                    @error('quantity')<span class="field-error">{{ $message }}</span>@enderror
                </fieldset>

                <fieldset class="form-section"><legend><span>০২</span> বিলিং তথ্য</legend>
                    <label for="customer-name">আপনার নাম</label><input id="customer-name" name="customer_name" type="text" autocomplete="name" value="{{ old('customer_name') }}" placeholder="নাম লিখুন" required maxlength="120">@error('customer_name')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="phone">মোবাইল নম্বর</label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="০১XXXXXXXXX" pattern="(?:\+?88)?01[3-9]\d{8}" required>@error('phone')<span class="field-error">{{ $message }}</span>@enderror
                    <label for="address">সম্পূর্ণ ঠিকানা</label><textarea id="address" name="address" autocomplete="street-address" rows="3" placeholder="বাড়ি, সড়ক, এলাকা" required maxlength="1000">{{ old('address') }}</textarea>@error('address')<span class="field-error">{{ $message }}</span>@enderror
                    <div class="form-two-columns"><div><label for="district">জেলা</label><select id="district" name="district_id" required><option value="">জেলা বেছে নিন</option>@foreach ($districts as $district)<option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $districtTranslations[$district->name] ?? $district->name }}</option>@endforeach</select>@error('district_id')<span class="field-error">{{ $message }}</span>@enderror</div><div><label for="area">এলাকা</label><select id="area" name="area_id" required disabled><option value="">আগে জেলা বেছে নিন</option>@foreach ($districts as $district)@foreach ($district->areas as $area)<option value="{{ $area->id }}" data-district="{{ $district->id }}" @selected(old('area_id') == $area->id)>{{ $areaTranslations[$area->name] ?? $area->name }}</option>@endforeach @endforeach</select>@error('area_id')<span class="field-error">{{ $message }}</span>@enderror</div></div>
                    <label for="customer-notes">অর্ডার সম্পর্কে কিছু বলার আছে? <span>(ঐচ্ছিক)</span></label><textarea id="customer-notes" name="customer_notes" rows="2" placeholder="প্রয়োজনীয় কোনো তথ্য লিখুন" maxlength="1000">{{ old('customer_notes') }}</textarea>
                </fieldset>

                <fieldset class="form-section shipping-section"><legend><span>০৩</span> ডেলিভারি ও পেমেন্ট</legend>
                    <p class="form-help">আপনার ঠিকানা অনুযায়ী ডেলিভারি এলাকা বেছে নিন।</p>
                    <div class="shipping-options">
                        @foreach ($shippingOptions as $index => $option)
                            @php($shippingLabel = $shippingTranslations[$option->key] ?? $option->name)
                            <label class="shipping-option"><input type="radio" name="shipping_option_id" value="{{ $option->id }}" data-charge="{{ $option->charge }}" @checked(old('shipping_option_id') == $option->id || (!old('shipping_option_id') && $index === 0)) required><span class="shipping-choice"><strong>{{ $shippingLabel }}</strong><small>{{ $loop->first ? 'সাধারণত ১–২ দিনে পৌঁছে যায়' : 'সাধারণত ২–৪ দিনে পৌঁছে যায়' }}</small></span><b>{{ number_format($option->charge) }} টাকা</b></label>
                        @endforeach
                    </div>
                    @error('shipping_option_id')<span class="field-error">{{ $message }}</span>@enderror
                    <label class="payment-choice"><input type="radio" name="payment_method" value="cod" checked required><span><strong>ক্যাশ অন ডেলিভারি</strong><small>পণ্য হাতে পেয়ে নগদে মূল্য পরিশোধ করুন।</small></span><span class="cod-mark">সিওডি</span></label>
                </fieldset>

                @if ($errors->any())<div class="form-error-summary" role="alert">অর্ডারের তথ্যগুলো ঠিক করে আবার চেষ্টা করুন।</div>@endif
                <button class="button button-dark place-order-button" type="submit" @disabled($product->stock_quantity < 1)>অর্ডার সম্পন্ন করুন — <span id="place-order-total">{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }} টাকা</span></button>
                <p class="secure-note">অনলাইনে পেমেন্ট লাগবে না। অর্ডার নিশ্চিত করতে আমরা যোগাযোগ করব।</p>
            </form>

            <aside class="order-summary" aria-live="polite" data-base-price="{{ $product->currentPrice() }}" data-regular-price="{{ $product->regular_price }}">
                <span class="eyebrow">আপনার অর্ডার</span><h3>অর্ডারের হিসাব</h3>
                <div class="summary-product"><img src="{{ $product->main_image_url }}" alt="" loading="lazy"><div><strong>{{ $productName }}</strong><span id="summary-variant">সাইজ বা অপশন বেছে নিন</span><span id="summary-quantity">পরিমাণ: {{ old('quantity', 1) }}</span></div><b id="summary-subtotal">{{ number_format($product->currentPrice()) }} টাকা</b></div>
                @if ($product->discount_price)<div class="summary-row discount-row"><span>আপনার সাশ্রয়</span><strong id="summary-discount">- {{ number_format($product->regular_price - $product->currentPrice()) }} টাকা</strong></div>@endif
                <div class="summary-row"><span>পণ্যের মূল্য</span><strong id="summary-subtotal-line">{{ number_format($product->currentPrice()) }} টাকা</strong></div>
                <div class="summary-row"><span id="summary-shipping-name">{{ isset($shippingTranslations[$shippingOptions->first()?->key]) ? $shippingTranslations[$shippingOptions->first()->key] : ($shippingOptions->first()?->name ?? 'ডেলিভারি') }}</span><strong id="summary-shipping">{{ number_format($shippingOptions->first()?->charge ?? 0) }} টাকা</strong></div>
                <div class="summary-total"><span>ডেলিভারিতে মোট দিতে হবে</span><strong id="summary-total">{{ number_format($product->currentPrice() + ($shippingOptions->first()?->charge ?? 0)) }} টাকা</strong></div>
                <p class="summary-footnote">অর্ডার নিশ্চিত করার সময় মূল্য আবার যাচাই করা হবে।</p>
            </aside>
        </div>
    </section>
</main>
@endsection
