@extends('layouts.storefront')

@section('title', 'SweetCrust | Everyday finds, chosen with care')

@section('content')
<main id="top">
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow"><i></i> THE SWEETCRUST EDIT · 01</span>
            <h1>Everyday style,<br><em>a little sweeter.</em></h1>
            <p>Thoughtfully picked clothes and accessories that bring comfort, color, and a little joy to your everyday.</p>
            <a class="button button-dark" href="#shop">Explore the collection <span>&#8599;</span></a>
            <div class="hero-proof"><span class="proof-stars">&#10003;</span><span>Easy ordering · Cash on delivery</span></div>
        </div>
        <div class="hero-visual" id="hero-slider" role="region" aria-roledescription="carousel" aria-label="SweetCrust featured stories">
            <div class="hero-slide is-active" data-slide="0" data-slide-label="SweetCrust video" role="group" aria-roledescription="slide" aria-hidden="false">
                <video class="hero-media" autoplay muted loop playsinline preload="metadata" poster="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85" aria-label="SweetCrust featured video">
                    <source src="{{ asset('videos/sweetcrust-slider.mp4') }}" type="video/mp4">
                </video>
                <div class="hero-caption"><span>A LITTLE SWEETNESS, IN MOTION</span><span>01 / 02</span></div>
            </div>
            <div class="hero-slide" data-slide="1" data-slide-label="Everyday style" role="group" aria-roledescription="slide" aria-hidden="true" inert>
                <img class="hero-media" src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85" alt="A relaxed, colorful everyday outfit" loading="lazy">
                <div class="hero-caption"><span>MADE FOR YOUR EVERYDAY</span><span>02 / 02</span></div>
            </div>
            <div class="hero-sticker"><span>THOUGHTFULLY PICKED</span><strong>For you</strong><span>Made to feel good</span></div>
            <div class="hero-slider-controls" aria-label="Featured slide controls">
                <button type="button" data-slider-prev aria-label="Previous slide">&#8592;</button>
                <div class="hero-slider-dots"><button type="button" data-slider-to="0" aria-label="Show video slide" aria-pressed="true"></button><button type="button" data-slider-to="1" aria-label="Show image slide" aria-pressed="false"></button></div>
                <button type="button" data-slider-next aria-label="Next slide">&#8594;</button>
                <span class="sr-only" id="hero-slide-status" aria-live="polite">Slide 1 of 2: SweetCrust video</span>
            </div>
        </div>
        <div class="hero-side-note">A little joy in every day</div>
    </section>

    <section class="promise-strip" id="delivery" aria-label="Shopping benefits">
        <div><span class="promise-icon">↺</span><span><b>Easy exchanges</b><small>Exchange within 7 days</small></span></div>
        <div><span class="promise-icon">৳</span><span><b>Cash on delivery</b><small>Pay when it arrives</small></span></div>
        <div><span class="promise-icon">✓</span><span><b>Checked with care</b><small>Quality checked before dispatch</small></span></div>
        <div><span class="promise-icon">⌖</span><span><b>Across Bangladesh</b><small>Delivery cost shown at checkout</small></span></div>
    </section>

    <section class="collection-section" id="shop">
        <div class="section-heading"><div><span class="eyebrow">A FEEL-GOOD EDIT</span><h2>Find your next <em>favorite.</em></h2><p class="section-intro">Thoughtful everyday pieces, ready to make your own. Choose a favorite and order right from its page.</p></div></div>
        <div class="shop-tools">
            <div class="filter-list" role="group" aria-label="Filter products">
                <button class="filter-chip active" data-category="ALL">All pieces <span>{{ $products->count() }}</span></button>
                <button class="filter-chip" data-category="WOMEN">Women</button>
                <button class="filter-chip" data-category="MEN">Men</button>
                <button class="filter-chip" data-category="ACCESSORIES">Accessories</button>
            </div>
            <label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg><input id="product-search" type="search" placeholder="Find something lovely..." aria-label="Search products"></label>
        </div>
        <div class="product-grid" id="product-grid">
            @forelse ($products as $product)
                @php($image = $product->images->first()?->image_url ?? $product->main_image_url)
                @php($categoryName = ['WOMEN' => 'Women', 'MEN' => 'Men', 'ACCESSORIES' => 'Accessories'][$product->category] ?? ucfirst(strtolower($product->category)))
                @php($productName = $product->name)
                <article class="product-card" data-category="{{ $product->category }}" data-name="{{ strtolower($productName) }}">
                    <a class="product-image-wrap" href="{{ route('products.show', $product) }}" aria-label="View {{ $productName }}">
                        <img src="{{ $image }}" alt="{{ $productName }}" loading="lazy">
                        @if ($product->featured)<span class="product-tag">A favorite</span>@endif
                        @if ($product->discountPercent())<span class="discount-tag">{{ $product->discountPercent() }}% off</span>@endif
                        <span class="quick-view">View details <b>&#8599;</b></span>
                    </a>
                    <div class="product-meta"><div><span class="product-category">{{ $categoryName }}</span><h3><a href="{{ route('products.show', $product) }}">{{ $productName }}</a></h3><p class="card-description">{{ $product->short_description }}</p></div></div>
                    <div class="product-bottom"><div class="price-line"><strong>৳{{ number_format($product->currentPrice()) }}</strong>@if ($product->discount_price)<del>৳{{ number_format($product->regular_price) }}</del>@endif</div><a class="order-now-link" href="{{ route('products.show', $product) }}">Choose yours <span>&#8599;</span></a></div>
                </article>
            @empty
                <p class="empty-catalog">A fresh collection is on its way. Please check back soon.</p>
            @endforelse
        </div>
        <p class="no-results" hidden>No pieces found. Try another search or category.</p>
    </section>

    <section class="story-section" id="story">
        <div class="story-image"><img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1100&q=85" alt="Friends enjoying a day out in their favorite looks" loading="lazy"><span class="image-caption">A LITTLE COMFORT. A LOT OF YOU.</span></div>
        <div class="story-copy"><span class="eyebrow">A NOTE FROM SWEETCRUST</span><h2>Good finds should feel <em>like you.</em></h2><p>We believe getting dressed can be simple, comfortable, and full of personality. SweetCrust brings together everyday pieces chosen for how they feel as much as how they look.</p><a class="text-link" href="#shop">Find your everyday favorite <span>&#8599;</span></a><span class="story-doodle">Made with care <span>♡</span></span></div>
    </section>

    <section class="why-section" id="why-sweetcrust" aria-labelledby="why-heading">
        <div class="why-heading"><span class="eyebrow">THE SWEETCRUST DIFFERENCE</span><h2 id="why-heading">Why choose <em>SweetCrust?</em></h2><p>A simple promise: thoughtful pieces, everyday comfort, and an easy way to shop.</p></div>
        <div class="why-grid">
            <article class="why-card"><span class="why-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M19.5 4.5C11 4.7 5.2 7.4 5.2 13.1a5.1 5.1 0 0 0 5.1 5.1c5.7 0 8.4-5.8 8.6-13.7Z"/><path d="M4.5 20c2.2-4 5.4-6.7 9.6-8.8"/></svg></span><h3>Picked with care</h3><p>Useful details, considered materials, and quality checks before each order is sent.</p></article>
            <article class="why-card"><span class="why-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3v8m0 0 5.7-5.7M12 11 6.3 5.3M5 13.5c0 4 2.8 6.5 7 6.5s7-2.5 7-6.5V12H5v1.5Z"/></svg></span><h3>Comfort for every day</h3><p>Easy-to-wear favorites made to fit naturally into workdays, weekends, and everything between.</p></article>
            <article class="why-card"><span class="why-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="6" y="3" width="12" height="18" rx="2"/><path d="M10 17h4"/></svg></span><h3>Simple online ordering</h3><p>Choose your options, enter your details, and review delivery charges before confirming.</p></article>
            <article class="why-card"><span class="why-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v10H3zM14 10h4l3 3v4h-7z"/><circle cx="7" cy="18" r="2"/><circle cx="18" cy="18" r="2"/><path d="M5 10h6"/></svg></span><h3>Delivered with care</h3><p>Cash on delivery across Bangladesh, with clear shipping costs and exchange support.</p></article>
        </div>
    </section>

    <section class="testimonial-section" id="kind-words" aria-labelledby="testimonial-heading">
        <div class="testimonial-heading"><span class="eyebrow">KIND WORDS</span><h2 id="testimonial-heading">What our customers <em>say.</em></h2><p>Sample testimonials for layout preview only. Replace these with verified customer reviews before publishing.</p></div>
        <div class="testimonial-grid">
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote>“The fabric feels lovely, and the relaxed fit is just right for everyday wear. I’m already planning another order.”</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-rose">S</span><span><strong>Sample customer</strong><small>Illustrative review</small></span></div></article>
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote>“Ordering was simple, and I could see the delivery charge before confirming. The whole process felt easy.”</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-olive">R</span><span><strong>Sample customer</strong><small>Illustrative review</small></span></div></article>
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="5 out of 5 stars">&#9733;&#9733;&#9733;&#9733;&#9734;</div><blockquote>“My scarf arrived just as pictured, neatly packed and ready to wear. A sweet little addition to my wardrobe.”</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-butter">A</span><span><strong>Sample customer</strong><small>Illustrative review</small></span></div></article>
        </div>
    </section>

    <section class="review-section" id="promise"><span class="eyebrow">FROM BROWSE TO DOORSTEP</span><div class="review-quote">A lovely find, a simple order, and delivery details you can see before you check out.</div><div class="review-byline"><span>Cash on delivery</span><i>·</i><span>Nationwide delivery</span><i>·</i><span>Easy 7-day exchanges</span></div></section>

    <section class="visit-section" id="visit" aria-labelledby="visit-heading">
        <div class="visit-details">
            <span class="eyebrow">SHOP FROM ANYWHERE</span>
            <h2 id="visit-heading">SweetCrust, <em>wherever you are.</em></h2>
            <p>We’re an online-first shop, bringing thoughtful everyday finds to customers across Bangladesh.</p>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">⌖</span><div><strong>Where we deliver</strong><span>Across Bangladesh, right to your door</span></div></div>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">◷</span><div><strong>Place an order</strong><span>Shop online anytime, whenever it suits you</span></div></div>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">↗</span><div><strong>Need a hand?</strong><a href="tel:+8801349576952">Call or WhatsApp +880 1349 576 952</a></div></div>
        </div>
        <div class="visit-card">
            <div class="visit-map" role="img" aria-label="Illustration representing SweetCrust delivery across Bangladesh">
                <span class="map-route map-route-one"></span><span class="map-route map-route-two"></span><span class="map-route map-route-three"></span>
                <span class="map-pin" aria-hidden="true"><i></i></span><span class="map-label">SWEETCRUST</span><span class="map-area map-area-one">DHAKA</span><span class="map-area map-area-two">BANGLADESH</span>
            </div>
            <div class="visit-card-footer"><div><strong>SweetCrust online</strong><span>Thoughtful finds, delivered nationwide.</span></div><a class="visit-directions" href="#shop">Explore the shop <span>↗</span></a></div>
        </div>
    </section>

    <section class="newsletter"><div><span class="eyebrow">A LITTLE SWEETNESS FOR YOUR DAY</span><h2>Find something that feels like you.</h2><p>Browse the collection and choose your next everyday favorite.</p></div><a class="button button-dark" href="#shop">Shop SweetCrust <span>&#8599;</span></a></section>
</main>
@endsection
