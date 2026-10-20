<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f5f0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Thoughtful everyday style, delivered across Bangladesh. Browse the collection and order directly from each product page.">
    <title>@yield('title', 'BSR')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/bsr-icon.svg') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-flow.css') }}">
    @stack('styles')
</head>
<body>
<div class="announcement"><span>Thoughtful style, delivered to your door</span><span class="announcement-note">Cash on delivery · Nationwide delivery</span></div>
<header class="site-header">
    <a class="brand" href="{{ route('storefront.home') }}" aria-label="BSR home"><img class="brand-logo" src="{{ asset('images/bsr-logo.svg') }}" alt="BSR"></a>
    <button class="menu-toggle" aria-label="Open navigation" aria-expanded="false"><span></span><span></span></button>
    <!-- <nav class="main-nav" aria-label="Main navigation">
        <a href="{{ route('storefront.home') }}#shop">Shop all</a>
        <a href="{{ route('storefront.home') }}#shop" data-filter-link="WOMEN">Women</a>
        <a href="{{ route('storefront.home') }}#shop" data-filter-link="MEN">Men</a>
        <a href="{{ route('storefront.home') }}#shop" data-filter-link="ACCESSORIES">Accessories</a>
    </nav> -->
    <div class="header-actions"><a href="{{ route('storefront.home') }}#shop" class="search-link" aria-label="Browse collection"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg></a><a class="header-order-link" href="{{ route('storefront.home') }}#shop">Order directly</a></div>
</header>
@yield('content')
<footer class="site-footer"><a class="brand footer-brand" href="{{ route('storefront.home') }}" aria-label="BSR home"><img class="brand-logo" src="{{ asset('images/bsr-logo.svg') }}" alt="BSR"></a><p>Everyday, considered.<br>Made for the way you live.</p><div class="footer-links"><div><span>EXPLORE</span><a href="{{ route('storefront.home') }}#shop">Shop all</a><a href="{{ route('admin.login') }}">Store admin</a></div><div><span>WE’RE HERE</span><a href="tel:+8801349576952">01349 576 952</a><a href="mailto:hello@usama.example">Send us a note</a><a href="{{ route('storefront.home') }}#delivery">Delivery & exchanges</a></div></div><div class="footer-bottom"><span>© {{ now()->year }} BSR. All good things reserved.</span><span>Thoughtfully made in Bangladesh <i>♡</i></span></div></footer>
<script src="{{ asset('js/storefront.js') }}" defer></script>
@stack('scripts')
</body>
</html>
