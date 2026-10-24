@extends('layouts.storefront')

@section('title', 'SweetCrust | Cakes, cheesecakes & fresh desserts')

@section('content')
<main id="top">
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow"><i></i> BAKED FRESH FOR YOUR MOMENTS</span>
            <h1>Make today<br><em>a little sweeter.</em></h1>
            <p>Celebration cakes, creamy cheesecakes, brownies and donuts, freshly prepared and delivered with care.</p>
            <a class="button button-dark" href="#shop">Explore cakes &amp; desserts <span>&#8599;</span></a>
            <div class="hero-proof"><span class="proof-stars">&#10003;</span><span>Freshly made &middot; Cash on delivery</span></div>
        </div>
        <div class="hero-visual" id="hero-slider" role="region" aria-roledescription="carousel" aria-label="SweetCrust bakery highlights">
            <div class="hero-slide is-active" data-slide="0" data-slide-label="SweetCrust bakery video" role="group" aria-roledescription="slide" aria-hidden="false">
                <video class="hero-media" autoplay muted loop playsinline preload="metadata" poster="{{ asset('images/products/Chocolate-Cake.jpg') }}" aria-label="SweetCrust featured video">
                    <source src="{{ asset('videos/sweetcrust-slider.mp4') }}" type="video/mp4">
                </video>
                <div class="hero-caption"><span>FRESHLY BAKED FOR YOUR MOMENTS</span><span>01 / 02</span></div>
            </div>
            <div class="hero-slide" data-slide="1" data-slide-label="Chocolate celebration cake" role="group" aria-roledescription="slide" aria-hidden="true" inert>
                <img class="hero-media" src="{{ asset('images/products/Chocolate-Cake.jpg') }}" alt="Fresh chocolate celebration cake" loading="lazy">
                <div class="hero-caption"><span>MADE FOR YOUR CELEBRATIONS</span><span>02 / 02</span></div>
            </div>
            <div class="hero-sticker"><span>BAKED WITH CARE</span><strong>Fresh daily</strong><span>For every sweet moment</span></div>
            <div class="hero-slider-controls" aria-label="Featured slide controls">
                <button type="button" data-slider-prev aria-label="Previous slide">&#8592;</button>
                <div class="hero-slider-dots"><button type="button" data-slider-to="0" aria-label="Show bakery video" aria-pressed="true"></button><button type="button" data-slider-to="1" aria-label="Show cake" aria-pressed="false"></button></div>
                <button type="button" data-slider-next aria-label="Next slide">&#8594;</button>
                <span class="sr-only" id="hero-slide-status" aria-live="polite">Slide 1 of 2: SweetCrust bakery video</span>
            </div>
        </div>
        <div class="hero-side-note">A little sweetness, made fresh</div>
    </section>

    <section class="promise-strip" id="delivery" aria-label="Bakery shopping benefits">
        <div><span class="promise-icon">&#10022;</span><span><b>Freshly prepared</b><small>Baked and packed with care</small></span></div>
        <div><span class="promise-icon">&#2547;</span><span><b>Cash on delivery</b><small>Pay when your order arrives</small></span></div>
        <div><span class="promise-icon">&#10003;</span><span><b>Made for sharing</b><small>Options for every gathering</small></span></div>
        <div><span class="promise-icon">&#10148;</span><span><b>Across Bangladesh</b><small>Delivery cost shown at checkout</small></span></div>
    </section>

    <section class="collection-section" id="shop">
        <div class="section-heading"><div><span class="eyebrow">FROM OUR OVEN TO YOUR TABLE</span><h2>Choose your <em>sweet moment.</em></h2><p class="section-intro">Find a celebration cake or a little treat to share. Choose a size and place your order in a few easy steps.</p></div></div>
        <div class="shop-tools">
            <div class="filter-list" role="group" aria-label="Filter products">
                <button class="filter-chip active" type="button" data-category="ALL" aria-pressed="true">Everything sweet <span>{{ $products->count() }}</span></button>
                <button class="filter-chip" type="button" data-category="CAKES" aria-pressed="false">Cakes</button>
                <button class="filter-chip" type="button" data-category="DESSERTS" aria-pressed="false">Desserts</button>
            </div>
            <label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg><input id="product-search" type="search" placeholder="Find a sweet favorite..." aria-label="Search cakes and desserts"></label>
        </div>
        <div class="product-grid" id="product-grid">
            @forelse ($products as $product)
                @php($image = $product->images->first()?->image_url ?? $product->main_image_url)
                @php($categoryName = $product->category === 'CAKES' ? 'Cakes' : 'Desserts')
                <article class="product-card" data-category="{{ $product->category }}" data-name="{{ strtolower($product->name) }}">
                    <a class="product-image-wrap" href="{{ route('products.show', $product) }}" aria-label="View {{ $product->name }}">
                        <img src="{{ $image }}" alt="{{ $product->name }}" loading="lazy">
                        @if ($product->featured)<span class="product-tag">Bakery favorite</span>@endif
                        @if ($product->discountPercent())<span class="discount-tag">{{ $product->discountPercent() }}% off</span>@endif
                        <span class="quick-view">View details <b>&#8599;</b></span>
                    </a>
                    <div class="product-meta"><div><span class="product-category">{{ $categoryName }}</span><h3><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></h3><p class="card-description">{{ $product->short_description }}</p></div></div>
                    <div class="product-bottom"><div class="price-line"><strong>&#2547;{{ number_format($product->currentPrice()) }}</strong>@if ($product->discount_price)<del>&#2547;{{ number_format($product->regular_price) }}</del>@endif</div><a class="order-now-link" href="{{ route('products.show', $product) }}">Choose a size <span>&#8599;</span></a></div>
                </article>
            @empty
                <p class="empty-catalog">Our oven is getting ready. Please check back soon.</p>
            @endforelse
        </div>
        <p class="no-results" hidden>No cakes or desserts found. Try another search or category.</p>
    </section>

    <section class="story-section" id="story">
        <div class="story-image"><img src="{{ asset('images/products/black-forest.jpeg') }}" alt="Black Forest cake made for sharing" loading="lazy"><span class="image-caption">A LITTLE JOY IN EVERY SLICE.</span></div>
        <div class="story-copy"><span class="eyebrow">A NOTE FROM SWEETCRUST</span><h2>Good days deserve <em>something sweet.</em></h2><p>We believe a cake can turn an ordinary afternoon into a memory. SweetCrust brings together comforting classics and little treats, prepared fresh and packed carefully for the people you love.</p><a class="text-link" href="#shop">Find your next favorite <span>&#8599;</span></a><span class="story-doodle">Made with care <span>&#9825;</span></span></div>
    </section>

    <section class="why-section" id="why-sweetcrust" aria-labelledby="why-heading">
        <div class="why-heading"><span class="eyebrow">THE SWEETCRUST PROMISE</span><h2>A little more <em>to love.</em></h2><p>Fresh bakes, thoughtful packing, and an easy way to send a treat to your table.</p></div>
        <div class="why-grid">
            <article class="why-card"><span class="why-icon">&#10022;</span><h3>Freshly prepared</h3><p>We prepare your cakes and desserts with care, ready for your celebration or everyday craving.</p></article>
            <article class="why-card"><span class="why-icon">&#9825;</span><h3>Made for sharing</h3><p>Choose a cake size or dessert box that fits your gathering and the people around your table.</p></article>
            <article class="why-card"><span class="why-icon">&#8599;</span><h3>Simple ordering</h3><p>Select your option, add delivery details, and review the full amount before confirming.</p></article>
            <article class="why-card"><span class="why-icon">&#10148;</span><h3>Delivered with care</h3><p>Cash on delivery across Bangladesh, with clear shipping costs shown before you place an order.</p></article>
        </div>
    </section>

    <section class="testimonial-section" id="kind-words" aria-labelledby="testimonial-heading">
        <div class="testimonial-heading"><span class="eyebrow">SWEET OCCASIONS</span><h2>Make room for <em>a treat.</em></h2><p>From birthdays and family visits to a quiet cup of tea, there is always a reason to share something lovely.</p></div>
        <div class="testimonial-grid">
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="Bakery favorite">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote>Celebrate a birthday with a cake made to bring everyone to the table.</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-rose">01</span><span><strong>Birthday moments</strong><small>Cakes for sharing</small></span></div></article>
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="Bakery favorite">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote>Bring a box of brownies or donuts when friends drop by.</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-olive">02</span><span><strong>Little get-togethers</strong><small>Easy dessert boxes</small></span></div></article>
            <article class="testimonial-card"><div class="testimonial-stars" aria-label="Bakery favorite">&#9733;&#9733;&#9733;&#9733;&#9733;</div><blockquote>Make an ordinary tea break feel like a small celebration.</blockquote><div class="testimonial-author"><span class="testimonial-avatar avatar-butter">03</span><span><strong>Everyday treats</strong><small>A sweet slice of the day</small></span></div></article>
        </div>
    </section>

    <section class="review-section" id="promise"><span class="eyebrow">FRESH BAKE TO YOUR DOOR</span><div class="review-quote">Choose a favorite, pick your size, and let us take care of the sweet part.</div><div class="review-byline"><span>Prepared fresh</span><i>&middot;</i><span>Cash on delivery</span><i>&middot;</i><span>Nationwide delivery</span></div></section>

    <section class="visit-section" id="visit" aria-labelledby="visit-heading">
        <div class="visit-details"><span class="eyebrow">SWEETCRUST DELIVERY</span><h2>A little sweetness, <em>wherever you are.</em></h2><p>Order online from anywhere in Bangladesh. Your delivery charge is shown clearly before you confirm.</p>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">&#10148;</span><div><strong>Where we deliver</strong><span>Across Bangladesh, right to your door</span></div></div>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">&#9200;</span><div><strong>Place an order</strong><span>Shop online anytime for your next occasion</span></div></div>
            <div class="visit-detail"><span class="visit-detail-icon" aria-hidden="true">&#9742;</span><div><strong>Need a hand?</strong><a href="tel:+8801349576952">Call or WhatsApp +880 1349 576 952</a></div></div>
        </div>
        <div class="visit-card"><div class="visit-map" role="img" aria-label="SweetCrust delivery across Bangladesh"><span class="map-route map-route-one"></span><span class="map-route map-route-two"></span><span class="map-route map-route-three"></span><span class="map-pin" aria-hidden="true"><i></i></span><span class="map-label">SWEETCRUST</span><span class="map-area map-area-one">DHAKA</span><span class="map-area map-area-two">BANGLADESH</span></div><div class="visit-card-footer"><div><strong>SweetCrust bakery</strong><span>Fresh desserts, delivered with care.</span></div><a class="visit-directions" href="#shop">Explore the menu <span>&#8599;</span></a></div></div>
    </section>

    <section class="newsletter"><div><span class="eyebrow">A LITTLE SWEETNESS FOR YOUR DAY</span><h2>There is always room for dessert.</h2><p>Browse our cakes and treats and choose something to share.</p></div><a class="button button-dark" href="#shop">Shop cakes &amp; desserts <span>&#8599;</span></a></section>
</main>
@endsection
