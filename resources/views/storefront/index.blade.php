@extends('layouts.storefront')

@section('title', 'বিএসআর | প্রতিদিনের পছন্দ')

@section('content')
<main id="top">
    <section class="hero">
        <div class="hero-copy"><span class="eyebrow"><i></i> প্রতিদিনের সংগ্রহ · ০১</span><h1>ভালো জিনিস,<br><em>যত্নে বেছে নিন।</em></h1><p>আপনার প্রতিদিনের জন্য আরামদায়ক ও সুন্দর পোশাক। সহজে পরুন, ভালোবেসে রাখুন।</p><a class="button button-dark" href="#shop">পণ্য দেখুন <span>&#8599;</span></a><div class="hero-proof"><span class="proof-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span>সহজ অর্ডার · ক্যাশ অন ডেলিভারি</span></div></div>
        <div class="hero-visual"><img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85" alt="প্রতিদিনের আরামদায়ক পোশাকে মডেল" fetchpriority="high"><div class="hero-caption"><span>সকালের সহজ সাজ</span><span>০১ / ০৪</span></div><div class="hero-sticker"><span>বাংলাদেশে তৈরি</span><strong>আপনার জন্য</strong><span>প্রতিদিনের সঙ্গী</span></div></div>
        <div class="hero-side-note">প্রতিদিন থাকুন নিজের মতো</div>
    </section>
    <section class="promise-strip" id="delivery" aria-label="ডেলিভারি ও অর্ডারের সুবিধা"><div><span class="promise-icon">↺</span><span><b>সহজে পণ্য বদল</b><small>৭ দিনের মধ্যে এক্সচেঞ্জ</small></span></div><div><span class="promise-icon">৳</span><span><b>ক্যাশ অন ডেলিভারি</b><small>পণ্য পেয়ে মূল্য পরিশোধ</small></span></div><div><span class="promise-icon">✓</span><span><b>মান যাচাই করা</b><small>পাঠানোর আগে প্রতিটি পণ্য পরীক্ষা</small></span></div><div><span class="promise-icon">⌖</span><span><b>সারা বাংলাদেশে</b><small>ডেলিভারি চার্জ আগে থেকেই জানা</small></span></div></section>
    <section class="collection-section" id="shop">
        <div class="section-heading"><div><span class="eyebrow">যত্নে বেছে নেওয়া সংগ্রহ</span><h2>আপনার প্রতিদিনের <em>পছন্দ।</em></h2><p class="section-intro">পণ্যের বিস্তারিত দেখুন এবং একই পেজ থেকে অর্ডার করুন।</p></div></div>
        <div class="shop-tools"><div class="filter-list" role="group" aria-label="পণ্য বাছাই"><button class="filter-chip active" data-category="ALL">সব পণ্য <span>{{ $products->count() }}</span></button><button class="filter-chip" data-category="WOMEN">নারীদের</button><button class="filter-chip" data-category="MEN">পুরুষদের</button><button class="filter-chip" data-category="ACCESSORIES">অনুষঙ্গ</button></div><label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg><input id="product-search" type="search" placeholder="পণ্য খুঁজুন..." aria-label="পণ্য খুঁজুন"></label></div>
        <div class="product-grid" id="product-grid">
            @forelse ($products as $product)
                @php($image = $product->images->first()?->image_url ?? $product->main_image_url)
                @php($catalogCopy = trans('catalog.'.$product->slug, [], 'bn'))
                @php($copy = is_array($catalogCopy) ? $catalogCopy : [])
                @php($categoryName = ['WOMEN' => 'নারীদের', 'MEN' => 'পুরুষদের', 'ACCESSORIES' => 'অনুষঙ্গ'][$product->category] ?? $product->category)
                @php($productName = filled($product->bangla_name) ? $product->bangla_name : $product->name)
                <article class="product-card" data-category="{{ $product->category }}" data-name="{{ strtolower($productName) }}">
                    <a class="product-image-wrap" href="{{ route('products.show', $product) }}" aria-label="{{ $productName }} দেখুন">
                        <img src="{{ $image }}" alt="{{ $productName }}" loading="lazy">
                        @if ($product->featured)<span class="product-tag">জনপ্রিয়</span>@endif
                        @if ($product->discountPercent())<span class="discount-tag">{{ $product->discountPercent() }}% ছাড়</span>@endif
                        <span class="quick-view">বিস্তারিত দেখুন <b>&#8599;</b></span>
                    </a>
                    <div class="product-meta"><div><span class="product-category">{{ $categoryName }}</span><h3><a href="{{ route('products.show', $product) }}">{{ $productName }}</a></h3><p class="card-description">{{ $copy['short'] ?? $product->short_description }}</p></div></div>
                    <div class="product-bottom"><div class="price-line"><strong>{{ number_format($product->currentPrice()) }} টাকা</strong>@if ($product->discount_price)<del>{{ number_format($product->regular_price) }} টাকা</del>@endif</div><a class="order-now-link" href="{{ route('products.show', $product) }}">অর্ডার করুন <span>&#8599;</span></a></div>
                </article>
            @empty
                <p class="empty-catalog">নতুন পণ্য শিগগিরই আসছে। পরে আবার দেখুন।</p>
            @endforelse
        </div>
        <p class="no-results" hidden>কোনো পণ্য পাওয়া যায়নি। অন্যভাবে খুঁজে দেখুন।</p>
    </section>
    <section class="review-section"><span class="eyebrow">সহজে অর্ডার করুন</span><div class="review-quote">পছন্দের পণ্য বেছে নিন, ঠিকানা দিন, অর্ডার করার আগেই জেনে নিন ডেলিভারিসহ মোট মূল্য।</div><div class="review-byline"><span class="review-stars">&#9733;&#9733;&#9733;&#9733;&#9733;</span><span>ব্যক্তিগত সহায়তা · ক্যাশ অন ডেলিভারি</span></div></section>
</main>
@endsection
