@extends('layouts.village-food')

@section('title', 'পাবনার প্যারা সন্দেশ | Village Food Zone')

@section('content')
@php($shippingLabels = ['inside-dhaka' => 'ঢাকার মধ্যে', 'outside-dhaka' => 'ঢাকার বাইরে'])
@php($selectedShipping = $shippingOptions->firstWhere('id', old('shipping_option_id')) ?? $shippingOptions->firstWhere('key', 'outside-dhaka') ?? $shippingOptions->first())
<div class="offer-bar"><span class="offer-spark" aria-hidden="true">✦</span> ৩ দিনের বিশেষ অফার <b>১ কেজি মাত্র ৳৬২০</b><span class="offer-spark" aria-hidden="true">✦</span></div>

<header class="vf-header">
    <a class="vf-brand" href="#top" aria-label="Village Food Zone — শুরুতে ফিরুন">
        <span class="vf-brand-mark" aria-hidden="true"><svg viewBox="0 0 48 48"><path d="M7 23 24 8l17 15v18H7z"/><path d="M18 41V27h12v14M4 23 24 5l20 18M13 19h.1M35 19h.1"/></svg></span>
        <span><strong>Village Food</strong><small>পাবনার ঐতিহ্য, আপনার টেবিলে</small></span>
    </a>
    <nav class="vf-nav" aria-label="প্রধান নেভিগেশন"><a href="#why-us">কেন আমাদের</a><a href="#process">প্রস্তুতপ্রণালি</a><a href="#order">অর্ডার</a><a href="#contact">যোগাযোগ</a></nav>
    <a class="vf-call-link" href="tel:01344180049"><span aria-hidden="true">☎</span> 01344-180049</a>
</header>

<main id="top">
    <section class="vf-hero" aria-labelledby="hero-title">
        <div class="vf-hero-copy">
            <span class="vf-kicker"><i></i> পাবনার ঐতিহ্যবাহী মিষ্টান্ন</span>
            <h1 id="hero-title">পাবনার<br><em>প্যারা সন্দেশ</em></h1>
            <p class="vf-hero-description">খাঁটি দুধে তৈরি পাবনার প্যারা সন্দেশ—প্রজন্মের ঐতিহ্যবাহী স্বাদ, যত্নে পৌঁছে দিই আপনার ঘরে।</p>
            <div class="vf-price-lockup"><span class="vf-old-price">৳৭০০</span><strong>৳৬২০</strong><span>/ ১ কেজি</span><b>৩ দিনের অফার</b></div>
            <div class="vf-hero-actions"><a class="vf-button vf-button-green" href="#order">অর্ডার করতে নিচে যান <span aria-hidden="true">↓</span></a><a class="vf-text-link" href="tel:01344180049">ফোনে অর্ডার: 01344-180049</a></div>
            <div class="vf-hero-proof"><span aria-hidden="true">✓</span> ক্যাশ অন ডেলিভারি <i>·</i> হাতে পেয়ে দেখে নিন</div>
        </div>
        <div class="vf-hero-art" id="hero-slider" role="region" aria-label="প্যারা সন্দেশ তৈরির ভিডিও">
            <div class="vf-art-wash"></div>
            <div class="vf-hero-viewport">
            <div class="hero-slide vf-hero-slide is-active" data-slide="0" data-slide-label="প্যারা সন্দেশ তৈরির ভিডিও" role="group" aria-roledescription="slide" aria-label="প্যারা সন্দেশ তৈরির ভিডিও" aria-hidden="false">
                <video class="vf-hero-media" autoplay loop controls muted playsinline preload="metadata" aria-label="প্যারা সন্দেশ তৈরির ভিডিও">
                    <source src="{{ asset('videos/para-sandesh-story.mp4') }}" type="video/mp4">
                    আপনার ব্রাউজারে ভিডিও চালানো যাচ্ছে না।
                </video>
            </div>
            </div>
        </div>
        <a class="vf-scroll-cue" href="#why-us"><span></span> আরও জানুন</a>
    </section>

    <section class="vf-trust-strip" aria-label="আমাদের বিশেষত্ব">
        <div><span class="vf-trust-icon">✳</span><span><strong>১০০% খাঁটি দুধ</strong><small>সেরা মানের উপকরণ</small></span></div>
        <div><span class="vf-trust-icon">✓</span><span><strong>রং ও কেমিক্যালমুক্ত</strong><small>সহজ, খাঁটি উপাদান</small></span></div>
        <div><span class="vf-trust-icon">⌂</span><span><strong>দেখে নিন, তারপর পরিশোধ</strong><small>রাইডারের সামনে যাচাই করুন</small></span></div>
        <div><span class="vf-trust-icon">৳</span><span><strong>ক্যাশ অন ডেলিভারি</strong><small>সারা বাংলাদেশে পৌঁছে দিই</small></span></div>
    </section>

    <section class="vf-story" id="why-us">
        <figure class="vf-story-photo"><img src="https://villagefoodzone.com/wp-content/uploads/2025/05/Sondesh-02-947x1024-1.jpg" alt="পাবনার প্যারা সন্দেশ" loading="lazy"></figure>
        <div class="vf-benefits">
            <header class="vf-benefits-heading"><span class="vf-kicker">আমাদের কাছ থেকে কেন নেবেন?</span><h2>সরল উপকরণ। <em>অসাধারণ স্বাদ।</em></h2></header>
            <article><span class="vf-benefit-number">০১</span><div><h3>সেরা মানের দুধ</h3><p>প্রতিদিন বাছাই করা দুধে তৈরি।</p></div></article>
            <article><span class="vf-benefit-number">০২</span><div><h3>রং ও কেমিক্যালমুক্ত</h3><p>কোনো কৃত্রিম রং বা কেমিক্যাল নেই।</p></div></article>
            <article><span class="vf-benefit-number">০৩</span><div><h3>সঠিক ওজন</h3><p>প্যাকেট বাদ দিয়ে শুধু সন্দেশের ওজন।</p></div></article>
            <article><span class="vf-benefit-number">০৪</span><div><h3>মাত্র দুই উপকরণ</h3><p>খাঁটি দুধ আর সামান্য চিনি।</p></div></article>
            <article><span class="vf-benefit-number">০৫</span><div><h3>ডেলিভারিতে দেখে নিন</h3><p>রাইডার থাকা অবস্থায় পণ্য যাচাই করুন।</p></div></article>
            <a class="vf-button vf-button-outline vf-benefits-cta" href="#order">অর্ডার করতে এগিয়ে যান <span aria-hidden="true">↓</span></a>
        </div>
    </section>

    <section class="vf-process-section" id="process" aria-labelledby="process-heading">
        <div class="vf-process-intro"><span class="vf-kicker">সহজ উপকরণ, ঐতিহ্যের ছোঁয়া</span><h2 id="process-heading">দুধ থেকে সন্দেশ—<br><em>যত্নে তৈরি প্রতিটি ধাপ।</em></h2><p>আমাদের প্যারা সন্দেশে অপ্রয়োজনীয় কিছু নেই। থাকে ভালো দুধ, সামান্য চিনি আর পাবনার চেনা কারিগরি।</p></div>
        <div class="vf-process-grid">
            <article class="vf-process-card"><span>উপকরণ</span><b class="vf-process-icon">◌</b><h3>খাঁটি দুধ ও সামান্য চিনি</h3><p>সহজ উপাদানে তৈরি—কোনো কৃত্রিম রং বা কেমিক্যাল নয়।</p></article>
            <article class="vf-process-card"><span>প্রস্তুতপ্রণালি</span><b class="vf-process-icon">⌁</b><h3>পাবনার ঐতিহ্যবাহী রেসিপি</h3><p>চেনা স্বাদের সেই ঐতিহ্য ধরে যত্নে তৈরি হয় প্রতিটি ব্যাচ।</p></article>
            <article class="vf-process-card"><span>প্যাকিং</span><b class="vf-process-icon">□</b><h3>ওজন করে যত্নে প্যাক</h3><p>প্যাকেটের ওজন বাদ দিয়ে শুধু সন্দেশ ওজন করে পাঠানো হয়।</p></article>
        </div>
    </section>

    <section class="vf-feedback-section" aria-labelledby="feedback-heading">
        <div class="vf-feedback-heading"><div><span class="vf-kicker">আপনাদের কথা শুনতে চাই</span><h2 id="feedback-heading">গ্রাহক মতামত ও <em>ভিডিও ফিডব্যাক</em></h2></div><p>আপনার অভিজ্ঞতা আমাদের সঙ্গে শেয়ার করুন—আপনার মতামতই আমাদের আরও ভালো হতে সাহায্য করে।</p></div>
        <div class="vf-feedback-grid">
            <article class="vf-feedback-card vf-feedback-featured"><img src="{{ asset('images/para-sandesh.svg') }}" alt="প্যারা সন্দেশের ভিডিও ফিডব্যাক" loading="lazy"><span class="vf-feedback-play" aria-hidden="true">▶</span><div><span>ভিডিও ফিডব্যাক</span><h3>আপনার অভিজ্ঞতা এখানে যোগ হবে</h3><p>সন্দেশ হাতে পেয়ে ছোট্ট একটি ভিডিও মতামত পাঠান।</p></div></article>
            <article class="vf-feedback-note"><span class="vf-feedback-quote" aria-hidden="true">“</span><span class="vf-kicker">আপনার মতামত গুরুত্বপূর্ণ</span><h3>স্বাদ কেমন লাগল?</h3><p>অর্ডার পাওয়ার পর আপনার সৎ মতামত ও ছবি আমাদের পাঠাতে পারেন। আমরা সেগুলো এখানে তুলে ধরব।</p><a href="tel:01344180049">মতামত জানাতে ফোন করুন <strong>01344-180049</strong></a></article>
            <article class="vf-feedback-card vf-feedback-prompt"><span class="vf-feedback-prompt-icon" aria-hidden="true">✦</span><span class="vf-kicker">আপনার গল্প বলুন</span><h3>পরের ফিচারড রিভিউটি হতে পারে আপনার</h3><p>পরিবারের সঙ্গে প্যারা সন্দেশ ভাগ করে নিন, তারপর আপনার অভিজ্ঞতা শেয়ার করুন।</p><a href="#order">অর্ডার করুন <span aria-hidden="true">↗</span></a></article>
        </div>
    </section>

    <section class="vf-order-section" id="order" aria-labelledby="order-heading">
        <div class="vf-order-heading"><span class="vf-kicker">ঘরে বসে অর্ডার করুন</span><h2 id="order-heading">আপনার মিষ্টির অর্ডার</h2><p>তথ্য দিন, ডেলিভারি বেছে নিন—মোট খরচ অর্ডারের আগেই দেখে নিন।</p></div>

        @if ($confirmation)
            <dialog class="vf-success-dialog" id="order-success-dialog" aria-labelledby="order-success-title">
                <section class="vf-success-card" role="status"><button class="success-close" id="close-order-success" type="button" aria-label="বন্ধ করুন">&times;</button><span class="vf-success-icon">✓</span><span class="vf-kicker">অর্ডার গ্রহণ করা হয়েছে</span><h2 id="order-success-title">ধন্যবাদ, {{ $confirmation['customer_name'] }}!</h2><p>আপনার অর্ডার নম্বর <strong>{{ $confirmation['order_number'] }}</strong>। নিশ্চিত করতে আমরা <strong>{{ $confirmation['phone'] }}</strong> নম্বরে ফোন করব।</p><p>ডেলিভারির সময় পরিশোধ: <strong>৳{{ number_format($confirmation['total']) }}</strong></p></section>
            </dialog>
        @endif

        @if ($featuredProduct)
            <div class="vf-order-grid">
                <form class="vf-form" method="POST" action="{{ route('products.orders.store', $featuredProduct) }}" id="order-form">
                    @csrf
                    <input type="hidden" name="return_to" value="home">
                    <fieldset class="vf-fieldset">
                        <legend><span>০১</span> পণ্যের পরিমাণ</legend>
                        <label for="product-variant">প্যাকেটের ওজন <b>*</b></label>
                        <select id="product-variant" name="product_variant_id" required @disabled($featuredProduct->stock_quantity < 1 || $featuredProduct->variants->isEmpty())>
                            <option value="">ওজন বেছে নিন</option>
                            @foreach ($featuredProduct->variants as $variant)
                                <option value="{{ $variant->id }}" data-adjustment="{{ $variant->price_adjustment }}" data-stock="{{ $variant->stock_quantity }}" @selected(old('product_variant_id') == $variant->id) @disabled($variant->stock_quantity < 1)>{{ $variant->value }}</option>
                            @endforeach
                        </select>
                        @error('product_variant_id')<span class="field-error">ওজনের অপশনটি আবার বেছে নিন।</span>@enderror
                        <label for="quantity">কত কেজি নেবেন?</label>
                        <div class="quantity-input vf-quantity"><button type="button" data-quantity-step="-1" aria-label="এক কমান">−</button><input id="quantity" name="quantity" type="number" min="1" max="20" value="{{ old('quantity', 1) }}" required><button type="button" data-quantity-step="1" aria-label="এক বাড়ান">+</button></div>
                        @error('quantity')<span class="field-error">এই পরিমাণ পণ্য মজুত নেই।</span>@enderror
                    </fieldset>

                    <fieldset class="vf-fieldset">
                        <legend><span>০২</span> আপনার ডেলিভারি ঠিকানা</legend>
                        <label for="customer-name">আপনার নাম <b>*</b></label><input id="customer-name" name="customer_name" type="text" autocomplete="name" value="{{ old('customer_name') }}" placeholder="সম্পূর্ণ নাম লিখুন" required maxlength="120">@error('customer_name')<span class="field-error">আপনার নাম লিখুন।</span>@enderror
                        <label for="address">সম্পূর্ণ ঠিকানা <b>*</b></label><textarea id="address" name="address" autocomplete="street-address" rows="2" placeholder="বাসা, সড়ক ও এলাকার নাম" required maxlength="1000">{{ old('address') }}</textarea>@error('address')<span class="field-error">সম্পূর্ণ ঠিকানা লিখুন।</span>@enderror
                        <label for="phone">মোবাইল নম্বর <b>*</b></label><input id="phone" name="phone" type="tel" autocomplete="tel" inputmode="tel" value="{{ old('phone') }}" placeholder="01XXXXXXXXX" pattern="(?:\+?88)?01[3-9]\d{8}" required>@error('phone')<span class="field-error">সঠিক মোবাইল নম্বর লিখুন।</span>@enderror
                        <div class="vf-two-columns">
                            <div><label for="district">জেলা <b>*</b></label><select id="district" name="district_id" required><option value="">জেলা বেছে নিন</option>@foreach ($districts as $district)<option value="{{ $district->id }}" @selected(old('district_id') == $district->id)>{{ $district->name }}</option>@endforeach</select>@error('district_id')<span class="field-error">জেলা বেছে নিন।</span>@enderror</div>
                            <div><label for="area">এলাকা <b>*</b></label><select id="area" name="area_id" required disabled><option value="">আগে জেলা বেছে নিন</option>@foreach ($districts as $district)@foreach ($district->areas as $area)<option value="{{ $area->id }}" data-district="{{ $district->id }}" @selected(old('area_id') == $area->id)>{{ $area->name }}</option>@endforeach @endforeach</select>@error('area_id')<span class="field-error">এলাকা বেছে নিন।</span>@enderror</div>
                        </div>
                        <label for="customer-notes">অর্ডার নোট <span>(ঐচ্ছিক)</span></label><textarea id="customer-notes" name="customer_notes" rows="2" placeholder="ডেলিভারির জন্য কোনো নির্দেশনা থাকলে লিখুন" maxlength="1000">{{ old('customer_notes') }}</textarea>
                    </fieldset>

                    <fieldset class="vf-fieldset vf-shipping-fieldset">
                        <legend><span>০৩</span> ডেলিভারি ও পেমেন্ট</legend>
                        <div class="vf-shipping-options">
                            @foreach ($shippingOptions as $shippingIndex => $option)
                                @php($shippingLabel = $shippingLabels[$option->key] ?? $option->name)
                                <label class="shipping-option vf-shipping-option"><input type="radio" name="shipping_option_id" value="{{ $option->id }}" data-charge="{{ $option->charge }}" @checked((string) old('shipping_option_id', $selectedShipping?->id) === (string) $option->id) required><span class="shipping-choice"><strong>{{ $shippingLabel }}</strong><small>{{ $option->key === 'inside-dhaka' ? 'ঢাকার ভেতরে ডেলিভারি' : 'ঢাকার বাইরে ডেলিভারি' }}</small></span><b>৳{{ number_format($option->charge) }}</b></label>
                            @endforeach
                        </div>
                        @error('shipping_option_id')<span class="field-error">ডেলিভারি এলাকা বেছে নিন।</span>@enderror
                        <label class="payment-choice vf-payment-choice"><input type="radio" name="payment_method" value="cod" checked required><span><strong>ক্যাশ অন ডেলিভারি</strong><small>পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন</small></span><span class="cod-mark">COD</span></label>
                    </fieldset>

                    @if ($errors->any())<div class="vf-error-summary" role="alert">কিছু তথ্য ঠিকভাবে দেওয়া হয়নি। অনুগ্রহ করে চিহ্নিত ঘরগুলো আবার যাচাই করুন।</div>@endif
                    <aside class="order-summary vf-summary" aria-live="polite" data-base-price="{{ $featuredProduct->currentPrice() }}" data-regular-price="{{ $featuredProduct->regular_price }}">
                        <span class="vf-kicker">আপনার অর্ডার</span><h3>খরচের বিবরণ</h3>
                        <div class="summary-product vf-summary-product"><img src="{{ $featuredProduct->main_image_url }}" alt="" loading="lazy"><div><strong>পাবনার প্যারা সন্দেশ</strong><span id="summary-variant">১ কেজি</span><span id="summary-quantity">পরিমাণ: {{ old('quantity', 1) }}</span></div><b id="summary-subtotal">৳{{ number_format($featuredProduct->currentPrice()) }}</b></div>
                        <div class="summary-row discount-row"><span>অফার সাশ্রয়</span><strong id="summary-discount">− ৳{{ number_format($featuredProduct->regular_price - $featuredProduct->currentPrice()) }}</strong></div>
                        <div class="summary-row"><span>পণ্যের মূল্য</span><strong id="summary-subtotal-line">৳{{ number_format($featuredProduct->currentPrice()) }}</strong></div>
                        <div class="summary-row"><span id="summary-shipping-name">{{ $shippingLabels[$selectedShipping?->key] ?? 'ডেলিভারি চার্জ' }}</span><strong id="summary-shipping">৳{{ number_format($selectedShipping?->charge ?? 0) }}</strong></div>
                        <div class="summary-total"><span>সর্বমোট</span><strong id="summary-total">৳{{ number_format($featuredProduct->currentPrice() + ($selectedShipping?->charge ?? 0)) }}</strong></div>
                        <p class="summary-footnote">অর্ডার নিশ্চিত করতে আমরা ফোনে যোগাযোগ করব।</p>
                    </aside>
                    <button class="vf-button vf-submit place-order-button" type="submit" id="home-place-order" @disabled($featuredProduct->stock_quantity < 1 || $featuredProduct->variants->isEmpty())>অর্ডার নিশ্চিত করুন <span id="place-order-total">৳{{ number_format($featuredProduct->currentPrice() + ($selectedShipping?->charge ?? 0)) }}</span></button>
                    <p class="vf-secure-note">🔒 অনলাইন পেমেন্টের দরকার নেই · ক্যাশ অন ডেলিভারি</p>
                </form>
                <aside class="vf-order-aside"><img src="{{ asset('images/para-sandesh.svg') }}" alt="" loading="lazy"><span class="vf-kicker">পাবনার স্বাদ, যত্নে পৌঁছে দিই</span><h3>অর্ডার করুন নিশ্চিন্তে</h3><ul><li>খাঁটি দুধে তৈরি</li><li>সঠিক ওজনের নিশ্চয়তা</li><li>রাইডারের সামনে দেখে নিন</li></ul><a href="tel:01344180049">ফোনে অর্ডার করুন <strong>01344-180049</strong></a></aside>
            </div>
        @else
            <p class="vf-unavailable">সন্দেশের অর্ডার সাময়িকভাবে বন্ধ আছে। বিস্তারিত জানতে 01344-180049 নম্বরে ফোন করুন।</p>
        @endif
    </section>

    <section class="vf-last-call"><div><span class="vf-kicker">পাবনার প্যারা সন্দেশ</span><h2>আজই পাঠিয়ে দিন <em>এক বাক্স মিষ্টি।</em></h2><p>নিজের জন্য, কিংবা প্রিয়জনের সঙ্গে ভাগ করে নিতে।</p></div><a class="vf-button vf-button-green" href="#order">অর্ডার করুন <span aria-hidden="true">↑</span></a></section>
</main>

<footer class="vf-footer" id="contact"><div class="vf-footer-brand"><span class="vf-brand-mark" aria-hidden="true">⌂</span><div><strong>Village Food Zone</strong><span>পাবনার ঐতিহ্য, আপনার টেবিলে</span></div></div><div class="vf-footer-contact"><span>যোগাযোগ</span><a href="tel:01344180049">01344-180049</a><span>চাটমোহর, পাবনা</span></div><div class="vf-footer-note">খাঁটি স্বাদ, যত্নের সঙ্গে।</div></footer>
<div class="vf-footer-bottom"><span>© {{ now()->year }} Village Food Zone</span><span>পাবনার ঐতিহ্যবাহী প্যারা সন্দেশ</span></div>
@endsection
