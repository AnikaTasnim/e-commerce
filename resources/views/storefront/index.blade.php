@extends('layouts.storefront')

@section('title', 'Usama — Thoughtful everyday style')

@section('content')
<main id="top">
    <section class="hero">
        <div class="hero-copy"><span class="eyebrow"><i></i> THE EVERYDAY EDIT — 01</span><h1>Good things,<br><em>well chosen.</em></h1><p>Thoughtful pieces for the days that make up your life. Easy to wear, lovely to keep.</p><a class="button button-dark" href="#shop">Explore the collection <span>↗</span></a><div class="hero-proof"><span class="proof-stars">★★★★★</span><span>Easy ordering · Cash on delivery</span></div></div>
        <div class="hero-visual"><img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85" alt="Model in a relaxed everyday look" fetchpriority="high"><div class="hero-caption"><span>THE SLOW MORNING LOOK</span><span>01 / 04</span></div><div class="hero-sticker"><span>MADE FOR</span><strong>real life</strong><span>IN BANGLADESH</span></div></div>
        <div class="hero-side-note">A LITTLE MORE YOU, EVERY DAY</div>
    </section>
    <section class="promise-strip" id="delivery" aria-label="Delivery and order benefits"><div><span class="promise-icon">✳</span><span><b>Easy exchanges</b><small>7-day exchange window</small></span></div><div><span class="promise-icon">⌁</span><span><b>Cash on delivery</b><small>Pay when it arrives</small></span></div><div><span class="promise-icon">♡</span><span><b>Quality checked</b><small>Before every dispatch</small></span></div><div><span class="promise-icon">↗</span><span><b>Across Bangladesh</b><small>Clear delivery charges</small></span></div></section>
    <section class="collection-section" id="shop">
        <div class="section-heading"><div><span class="eyebrow">A CONSIDERED COLLECTION</span><h2>Find your <em>everyday.</em></h2><p class="section-intro">Choose a piece to see its full details and place your order on the same page.</p></div></div>
        <div class="shop-tools"><div class="filter-list" role="group" aria-label="Filter products"><button class="filter-chip active" data-category="ALL">Everything <span>{{ $products->count() }}</span></button><button class="filter-chip" data-category="WOMEN">Women</button><button class="filter-chip" data-category="MEN">Men</button><button class="filter-chip" data-category="ACCESSORIES">Accessories</button></div><label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg><input id="product-search" type="search" placeholder="Find something..." aria-label="Search products"></label></div>
        <div class="product-grid" id="product-grid">
            @forelse ($products as $product)
                @php($image = $product->images->first()?->image_path ?? $product->main_image)
                <article class="product-card" data-category="{{ $product->category }}" data-name="{{ strtolower($product->name) }}">
                    <a class="product-image-wrap" href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }}">
                        <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
                        @if ($product->featured)<span class="product-tag">Bestseller</span>@endif
                        @if ($product->discountPercent())<span class="discount-tag">−{{ $product->discountPercent() }}%</span>@endif
                        <span class="quick-view">View product <b>↗</b></span>
                    </a>
                    <div class="product-meta"><div><span class="product-category">{{ $product->category }}</span><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3><p class="card-description">{{ $product->short_description }}</p></div></div>
                    <div class="product-bottom"><div class="price-line"><strong>&#2547;{{ number_format($product->currentPrice()) }}</strong>@if ($product->discount_price)<del>&#2547;{{ number_format($product->regular_price) }}</del>@endif</div><a class="order-now-link" href="{{ route('products.show', $product) }}">Order now <span>↗</span></a></div>
                </article>
            @empty
                <p class="empty-catalog">Our next collection is on the way. Please check back soon.</p>
            @endforelse
        </div>
        <p class="no-results" hidden>No pieces found. Try another search.</p>
    </section>
    <section class="review-section"><span class="eyebrow">A SIMPLE WAY TO ORDER</span><div class="review-quote">Choose your piece, add your details, and see the full delivered price before you place your order.</div><div class="review-byline"><span class="review-stars">★★★★★</span><span>Personal support <i>·</i> Cash on delivery</span></div></section>
</main>
@endsection
