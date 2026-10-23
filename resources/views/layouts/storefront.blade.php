<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#fff7ed">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Thoughtful everyday essentials from SweetCrust, delivered across Bangladesh. Explore the collection and order with cash on delivery.">
    <title>@yield('title', 'SweetCrust')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/deshicart-icon.png') }}?v=2">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-flow.css') }}">
    @stack('styles')
</head>
<body>
<div class="announcement"><span>Thoughtful finds, delivered to your door</span><span class="announcement-note">Cash on delivery · Nationwide shipping</span></div>
<header class="site-header">
    <a class="brand" href="{{ route('storefront.home') }}" aria-label="SweetCrust home"><img class="brand-logo" src="{{ asset('images/sweet-crust-wordmark.png') }}?v=1" alt="SweetCrust"></a>
    <nav class="main-nav" aria-label="Main navigation"><a href="{{ route('storefront.home') }}#shop">Shop</a><a href="{{ route('storefront.home') }}#story">Our Story</a><a href="{{ route('storefront.home') }}#why-sweetcrust">Why SweetCrust</a><a href="{{ route('storefront.home') }}#promise">Our Promise</a></nav>
    <div class="header-actions"><a href="{{ route('storefront.home') }}#shop" class="search-link" aria-label="Browse products"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg></a><a class="header-order-link" href="{{ route('storefront.home') }}#shop">Shop the collection</a></div>
</header>
@yield('content')
<footer class="site-footer"><a class="brand footer-brand" href="{{ route('storefront.home') }}" aria-label="SweetCrust home"><img class="brand-logo" src="{{ asset('images/sweet-crust-wordmark.png') }}?v=1" alt="SweetCrust"></a><p>Everyday favorites,<br>chosen with care.</p><div class="footer-links"><div><span>EXPLORE</span><a href="{{ route('storefront.home') }}#shop">Shop all</a><a href="{{ route('storefront.home') }}#story">Our story</a><a href="{{ route('admin.login') }}">Store admin</a></div><div><span>GET IN TOUCH</span><a href="tel:+8801349576952">+880 1349 576 952</a><a href="mailto:hello@usama.example">Email our team</a><a href="{{ route('storefront.home') }}#delivery">Delivery &amp; exchanges</a></div></div><div class="footer-bottom"><span>© {{ now()->year }} SweetCrust. All rights reserved.</span><span>Made with care in Bangladesh <i>♡</i></span></div></footer>
<script src="{{ asset('js/storefront.js') }}" defer></script>
@stack('scripts')
</body>
</html>
