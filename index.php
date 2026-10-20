<?php
// Temporary storefront content for the frontend milestone. Replace with Laravel data later.
$products = [
    ['id' => 'linen-shirt', 'name' => 'Everyday Linen Shirt', 'category' => 'Men', 'price' => 1490, 'was' => 1890, 'color' => 'Sand / Olive / White', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'Bestseller', 'image' => 'photo-1598033129183-c4f50c736f10', 'tone' => 'sage', 'description' => 'Easy, breathable linen-blend comfort for long days and slow weekends. A relaxed fit, thoughtful details, and a feel you will reach for again.' ],
    ['id' => 'studio-kurti', 'name' => 'Studio Cotton Kurti', 'category' => 'Women', 'price' => 1790, 'was' => 2190, 'color' => 'Indigo / Ivory', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'New arrival', 'image' => 'photo-1585487000160-6ebcfceb0d03', 'tone' => 'rose', 'description' => 'A modern everyday essential in soft cotton, finished with a clean silhouette that moves easily from workdays to weekends.' ],
    ['id' => 'weekend-polo', 'name' => 'Weekend Knit Polo', 'category' => 'Men', 'price' => 1290, 'was' => 1590, 'color' => 'Navy / Cream', 'sizes' => ['M', 'L', 'XL', 'XXL'], 'tag' => 'Limited offer', 'image' => 'photo-1625910513413-5fc45e3c5e5a', 'tone' => 'blue', 'description' => 'A soft textured knit polo with a polished collar and an easy, comfortable fit. Made for everyday plans.' ],
    ['id' => 'soft-weave-scarf', 'name' => 'Soft Weave Scarf', 'category' => 'Accessories', 'price' => 790, 'was' => 990, 'color' => 'Terracotta / Sand', 'sizes' => ['One size'], 'tag' => 'Easy favorite', 'image' => 'photo-1601924994987-69e26d50dc26', 'tone' => 'clay', 'description' => 'A lightweight layer with a subtle woven texture. An effortless finishing touch, season after season.' ],
    ['id' => 'city-tote', 'name' => 'Daily Carry Tote', 'category' => 'Accessories', 'price' => 1090, 'was' => 1390, 'color' => 'Natural / Black', 'sizes' => ['One size'], 'tag' => 'Bestseller', 'image' => 'photo-1590874103328-eac38a683ce7', 'tone' => 'sand', 'description' => 'Room for the everyday, with a clean profile and sturdy handles. Your new dependable plus-one.' ],
    ['id' => 'relaxed-trouser', 'name' => 'Relaxed Tailored Trouser', 'category' => 'Women', 'price' => 1590, 'was' => 1990, 'color' => 'Stone / Black', 'sizes' => ['S', 'M', 'L', 'XL'], 'tag' => 'Just landed', 'image' => 'photo-1506629905607-d9c297d8f0c8', 'tone' => 'cream', 'description' => 'A comfortable high-rise shape with a clean, tailored finish. An easy match for all your favorite tops.' ],
];
$money = static fn (int $amount): string => '৳' . number_format($amount);
$discount = static fn (array $product): int => (int) round((1 - $product['price'] / $product['was']) * 100);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f5f0">
    <meta name="description" content="Thoughtful everyday style, made easy. Discover a considered edit of wardrobe essentials with delivery across Bangladesh.">
    <title>Usama — Everyday, considered.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="announcement"><span>Thoughtful style, delivered to your door</span><span class="announcement-note">Free delivery on orders over ৳2,500</span></div>
<header class="site-header">
    <a class="brand" href="#top" aria-label="Usama home"><span class="brand-mark">U</span><span>usama<span class="brand-period">.</span></span></a>
    <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span></button>
    <nav class="main-nav" aria-label="Main navigation">
        <a href="#shop">Shop all</a><a href="#shop" data-filter-link="Women">Women</a><a href="#shop" data-filter-link="Men">Men</a><a href="#shop" data-filter-link="Accessories">Accessories</a>
    </nav>
    <div class="header-actions"><a href="#shop" class="search-link" aria-label="Browse collection"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg></a><button class="bag-button" type="button" aria-label="View your bag"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 8h14l1 12H4L5 8Z"/><path d="M9 9V6a3 3 0 0 1 6 0v3"/></svg><span class="bag-count">0</span></button></div>
</header>
<main id="top">
    <section class="hero">
        <div class="hero-copy"><span class="eyebrow"><i></i> THE EVERYDAY EDIT — 01</span><h1>Good things,<br><em>well chosen.</em></h1><p>Thoughtful pieces for the days that make up your life. Easy to wear, lovely to keep.</p><a class="button button-dark" href="#shop">Explore the collection <span>↗</span></a><div class="hero-proof"><span class="proof-stars">★★★★★</span><span>Loved by 2,000+ happy customers</span></div></div>
        <div class="hero-visual"><img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=1200&q=85" alt="Model wearing a timeless neutral outfit" fetchpriority="high"><div class="hero-caption"><span>THE SLOW MORNING LOOK</span><span>01 / 04</span></div><div class="hero-sticker"><span>MADE FOR</span><strong>real life</strong><span>IN BANGLADESH</span></div></div>
        <div class="hero-side-note">A LITTLE MORE YOU, EVERY DAY</div>
    </section>
    <section class="promise-strip" aria-label="Shopping benefits"><div><span class="promise-icon">✳</span><span><b>Easy returns</b><small>7-day exchange window</small></span></div><div><span class="promise-icon">⌁</span><span><b>Cash on delivery</b><small>Pay when it arrives</small></span></div><div><span class="promise-icon">♡</span><span><b>Made to feel good</b><small>Comfort comes first</small></span></div><div><span class="promise-icon">↗</span><span><b>Across Bangladesh</b><small>We deliver nationwide</small></span></div></section>
    <section class="collection-section" id="shop">
        <div class="section-heading"><div><span class="eyebrow">A CONSIDERED COLLECTION</span><h2>Find your <em>everyday.</em></h2></div><a class="text-link desktop-link" href="#shop">Shop all pieces <span>↗</span></a></div>
        <div class="shop-tools"><div class="filter-list" role="group" aria-label="Filter products"><button class="filter-chip active" data-category="All">Everything <span><?= count($products) ?></span></button><button class="filter-chip" data-category="Women">Women</button><button class="filter-chip" data-category="Men">Men</button><button class="filter-chip" data-category="Accessories">Accessories</button></div><label class="search-box"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg><input id="product-search" type="search" placeholder="Find something..." aria-label="Search products"></label></div>
        <div class="product-grid" id="product-grid">
            <?php foreach ($products as $product): ?>
                <article class="product-card" data-category="<?= htmlspecialchars($product['category']) ?>" data-name="<?= htmlspecialchars(strtolower($product['name'])) ?>">
                    <button class="product-image-wrap" type="button" data-product="<?= htmlspecialchars($product['id']) ?>" aria-label="View <?= htmlspecialchars($product['name']) ?>">
                        <img src="https://images.unsplash.com/<?= htmlspecialchars($product['image']) ?>?auto=format&fit=crop&w=760&q=80" alt="<?= htmlspecialchars($product['name']) ?>" loading="lazy">
                        <span class="product-tag"><?= htmlspecialchars($product['tag']) ?></span><span class="discount-tag">−<?= $discount($product) ?>%</span><span class="quick-view">Quick view <b>↗</b></span>
                    </button>
                    <div class="product-meta"><div><span class="product-category"><?= htmlspecialchars($product['category']) ?></span><h3><button type="button" data-product="<?= htmlspecialchars($product['id']) ?>"><?= htmlspecialchars($product['name']) ?></button></h3></div><button class="save-button" type="button" aria-label="Save <?= htmlspecialchars($product['name']) ?>">♡</button></div>
                    <div class="product-bottom"><div class="price-line"><strong><?= $money($product['price']) ?></strong><del><?= $money($product['was']) ?></del></div><button class="add-button" type="button" data-add="<?= htmlspecialchars($product['id']) ?>" aria-label="Add <?= htmlspecialchars($product['name']) ?> to bag">+</button></div>
                </article>
            <?php endforeach; ?>
        </div>
        <p class="no-results" hidden>No pieces found. Try another search.</p>
        <a class="text-link mobile-shop-link" href="#shop">See the collection <span>↗</span></a>
    </section>
    <section class="story-section"><div class="story-image"><img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=1100&q=80" alt="Friends enjoying a relaxed day together" loading="lazy"><span class="image-caption">A GOOD DAY, WELL DRESSED.</span></div><div class="story-copy"><span class="eyebrow">A NOTE FROM US</span><h2>Less, but a little<br><em>more meaningful.</em></h2><p>We believe getting dressed should feel simple. So we look for the pieces you’ll reach for again: comfortable fits, thoughtful details, and a little room to be yourself.</p><a href="#shop" class="text-link">Get to know your next favorite <span>↗</span></a><div class="story-doodle">made with care <span>♡</span></div></div></section>
    <section class="review-section"><span class="eyebrow">KIND WORDS, REAL PEOPLE</span><div class="review-quote">“It feels like they actually thought about how clothes fit into a real day. The fabric is lovely and ordering was so easy.”</div><div class="review-byline"><span class="review-stars">★★★★★</span><span>Rafia A. <i>·</i> Dhaka</span></div><div class="review-count">01 <span></span> 03</div></section>
    <section class="newsletter"><div><span class="eyebrow">A NOTE NOW AND THEN</span><h2>Good things in your <em>inbox.</em></h2><p>New arrivals, little stories, and 10% off your first order.</p></div><form class="newsletter-form"><label class="sr-only" for="email">Email address</label><input id="email" type="email" placeholder="Your email address" required><button type="submit" aria-label="Subscribe">↗</button><small>By subscribing, you agree to receive occasional notes from us.</small></form></section>
</main>
<footer class="site-footer"><a class="brand footer-brand" href="#top"><span class="brand-mark">U</span><span>usama<span class="brand-period">.</span></span></a><p>Everyday, considered.<br>Made for the way you live.</p><div class="footer-links"><div><span>EXPLORE</span><a href="#shop">Shop all</a><a href="#shop">New arrivals</a><a href="#shop">Our story</a></div><div><span>WE’RE HERE</span><a href="tel:+8801349576952">01349 576 952</a><a href="mailto:hello@usama.example">Send us a note</a><a href="#shop">Delivery & exchanges</a></div></div><div class="footer-bottom"><span>© <?= date('Y') ?> Usama. All good things reserved.</span><span>Thoughtfully made in Bangladesh <i>♡</i></span></div></footer>

<dialog class="product-dialog" id="product-dialog"><button class="dialog-close" type="button" aria-label="Close">×</button><div class="dialog-content"></div></dialog>
<dialog class="bag-dialog" id="bag-dialog"><button class="dialog-close" type="button" aria-label="Close">×</button><span class="eyebrow">YOUR LITTLE EDIT</span><h2>Your bag <em>(<span id="bag-total-count">0</span>)</em></h2><div class="bag-items"></div><div class="bag-footer"><div class="bag-subtotal"><span>Subtotal</span><strong id="bag-subtotal">৳0</strong></div><p>Delivery charge is calculated at checkout.</p><button class="button button-dark checkout-button" type="button">Continue to order <span>↗</span></button></div></dialog>
<dialog class="checkout-dialog" id="checkout-dialog"><button class="dialog-close" type="button" aria-label="Close">×</button><div class="checkout-content"><span class="eyebrow">A FEW DETAILS</span><h2>Where should we <em>send it?</em></h2><p class="checkout-intro">Cash on delivery, nice and simple. We’ll call to confirm your order.</p><form id="checkout-form" class="checkout-form"><label>Your name<input name="name" autocomplete="name" required placeholder="e.g. Samira Rahman"></label><label>Phone number<input name="phone" inputmode="tel" autocomplete="tel" pattern="(?:\+?88)?01[3-9]\d{8}" required placeholder="01XXXXXXXXX"></label><label>Delivery address<textarea name="address" autocomplete="street-address" required rows="3" placeholder="House, road, area"></textarea></label><label>District<select name="district" required><option value="">Choose a district</option><option>Dhaka</option><option>Chattogram</option><option>Sylhet</option><option>Rajshahi</option><option>Khulna</option><option>Other</option></select></label><label>Order note <span class="optional">Optional</span><textarea name="notes" rows="2" placeholder="Anything we should know?"></textarea></label><div class="checkout-total"><span>Demo order total</span><strong id="checkout-total">৳0</strong></div><button class="button button-dark" type="submit">Place demo order <span>↗</span></button><small>This is a frontend preview. It won’t save or submit your information.</small></form></div></dialog>
<div class="toast" role="status" aria-live="polite"></div>
<script>window.storeProducts = <?= json_encode($products, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;</script>
<script src="app.js" defer></script>
</body>
</html>
