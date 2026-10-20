<!doctype html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f5f0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="প্রতিদিনের পছন্দের পোশাক ও অনুষঙ্গ, সারা বাংলাদেশে পৌঁছে দিচ্ছি। পণ্য দেখুন এবং সরাসরি অর্ডার করুন।">
    <title>@yield('title', 'বিএসআর')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/bsr-icon.svg') }}?v=1">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <link rel="stylesheet" href="{{ asset('css/order-flow.css') }}">
    @stack('styles')
</head>
<body>
<div class="announcement"><span>আপনার দোরগোড়ায় পৌঁছে দিচ্ছি পছন্দের পণ্য</span><span class="announcement-note">ক্যাশ অন ডেলিভারি · সারা দেশে ডেলিভারি</span></div>
<header class="site-header">
    <a class="brand" href="{{ route('storefront.home') }}" aria-label="বিএসআর হোম"><img class="brand-logo" src="{{ asset('images/bsr-logo.svg') }}" alt="বিএসআর"></a>
    <div class="header-actions"><a href="{{ route('storefront.home') }}#shop" class="search-link" aria-label="পণ্য দেখুন"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.4 4.4"/></svg></a><a class="header-order-link" href="{{ route('storefront.home') }}#shop">সরাসরি অর্ডার করুন</a></div>
</header>
@yield('content')
<footer class="site-footer"><a class="brand footer-brand" href="{{ route('storefront.home') }}" aria-label="বিএসআর হোম"><img class="brand-logo" src="{{ asset('images/bsr-logo.svg') }}" alt="বিএসআর"></a><p>প্রতিদিনের জন্য<br>যত্নে বেছে নেওয়া।</p><div class="footer-links"><div><span>ঘুরে দেখুন</span><a href="{{ route('storefront.home') }}#shop">সব পণ্য</a><a href="{{ route('admin.login') }}">স্টোর অ্যাডমিন</a></div><div><span>যোগাযোগ</span><a href="tel:+8801349576952">০১৩৪৯ ৫৭৬ ৯৫২</a><a href="mailto:hello@usama.example">আমাদের লিখুন</a><a href="{{ route('storefront.home') }}#delivery">ডেলিভারি ও পণ্য বদল</a></div></div><div class="footer-bottom"><span>© {{ now()->year }} বিএসআর। সর্বস্বত্ব সংরক্ষিত।</span><span>বাংলাদেশে যত্নে তৈরি <i>♡</i></span></div></footer>
<script src="{{ asset('js/storefront.js') }}" defer></script>
@stack('scripts')
</body>
</html>
