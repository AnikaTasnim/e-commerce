<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f4f7fb">
    <title>@yield('title', 'Admin dashboard � BSR')</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/bsr-icon.svg') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body class="admin-body @guest admin-auth-page @endguest">
@auth
<div class="admin-shell">
    <aside class="admin-sidebar" id="admin-sidebar">
        <a class="admin-brand" href="{{ route('admin.dashboard') }}"><span class="admin-brand-mark">B</span><span><strong>BSR</strong><small>STORE CONTROL</small></span></a>
        <div class="admin-side-label">WORKSPACE</div>
        <nav class="admin-side-nav" aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><span class="nav-icon">�</span>Dashboard</a>
            <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])><span class="nav-icon">?</span>Products</a>
            <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])><span class="nav-icon">?</span>Orders</a>
            <div class="admin-side-label settings-label">CONFIGURATION</div>
            <a href="{{ route('admin.shipping.index') }}" @class(['active' => request()->routeIs('admin.shipping.*')])><span class="nav-icon">?</span>Shipping settings</a>
        </nav>
        <div class="admin-sidebar-bottom"><a href="{{ route('storefront.home') }}" target="_blank" rel="noopener"><span class="nav-icon">?</span>View storefront</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit"><span class="nav-icon">?</span>Sign out</button></form></div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar"><button class="admin-menu-toggle" id="admin-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">?</button><div class="admin-crumb">BSR <span>/</span> <strong>@yield('section', 'Overview')</strong></div><div class="admin-topbar-right"><span class="admin-live-dot"></span><span>Store admin</span><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span></div></header>
        <div class="admin-content">@yield('content')</div>
        <footer class="admin-footer">BSR Store Management <span>�</span> {{ now()->year }}</footer>
    </div>
</div>
@else
    @yield('content')
@endauth
<script>
const menuButton=document.getElementById('admin-menu-toggle');
if(menuButton){menuButton.addEventListener('click',()=>{document.body.classList.toggle('admin-sidebar-open');menuButton.setAttribute('aria-expanded',document.body.classList.contains('admin-sidebar-open')?'true':'false')})}
</script>
@stack('scripts')
</body>
</html>
