<!doctype html>
<html lang="bn">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#3d291a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="পাবনার ঐতিহ্যবাহী প্যারা সন্দেশ—খাঁটি দুধে তৈরি, সারা বাংলাদেশে ক্যাশ অন ডেলিভারিতে অর্ডার করুন।">
    <title>@yield('title', 'Village Food Zone | পাবনার প্যারা সন্দেশ')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/order-flow.css') }}">
    <link rel="stylesheet" href="{{ asset('css/village-food.css') }}?v=5">
</head>
<body>
    @yield('content')
    <script src="{{ asset('js/storefront.js') }}?v=4" defer></script>
</body>
</html>
