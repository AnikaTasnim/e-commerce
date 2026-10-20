<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#f4f7fb">
    <title>@yield('title', 'BSR Admin Dashboard')</title>
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
            <a href="{{ route('admin.dashboard') }}" @class(['active' => request()->routeIs('admin.dashboard')])><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/></svg></span>Dashboard</a>
            <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="6" height="6" rx="1"/><rect x="14" y="4" width="6" height="6" rx="1"/><rect x="4" y="14" width="6" height="6" rx="1"/><rect x="14" y="14" width="6" height="6" rx="1"/></svg></span>Products</a>
            <a href="{{ route('admin.orders.index') }}" @class(['active' => request()->routeIs('admin.orders.*')])><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3h10l3 3v15H4V3z"/><path d="M8 10h8M8 14h8M8 18h5M14 3v4h5"/></svg></span>Orders</a>
            <a href="{{ route('admin.reports.sales') }}" @class(['active' => request()->routeIs('admin.reports.*')])><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 20V4M4 20h17"/><path d="m7 15 4-4 3 2 6-7"/><path d="M17 6h3v3"/></svg></span>Sales reports</a>
            <div class="admin-side-label settings-label">CONFIGURATION</div>
            <a href="{{ route('admin.shipping.index') }}" @class(['active' => request()->routeIs('admin.shipping.*')])><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7h11v11H3zM14 11h4l3 3v4h-7z"/><circle cx="7.5" cy="19" r="1.5"/><circle cx="17.5" cy="19" r="1.5"/></svg></span>Shipping settings</a>
        </nav>
        <div class="admin-sidebar-bottom"><a href="{{ route('storefront.home') }}" target="_blank" rel="noopener"><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 4h6v6M20 4l-9 9"/><path d="M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/></svg></span>View storefront</a><form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit"><span class="nav-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h5M14 16l4-4-4-4M8 12h10"/></svg></span>Sign out</button></form></div>
    </aside>
    <div class="admin-main">
        <header class="admin-topbar"><button class="admin-menu-toggle" id="admin-menu-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button><div class="admin-crumb">BSR <span>/</span> <strong>@yield('section', 'Overview')</strong></div><div class="admin-topbar-right"><span class="admin-live-dot"></span><span>Store admin</span><span class="admin-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</span></div></header>
        <div class="admin-content">@yield('content')</div>
        <footer class="admin-footer">BSR Store Management <span>/</span> {{ now()->year }}</footer>
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
