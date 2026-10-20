<nav class="admin-nav" aria-label="Admin navigation">
    <a href="{{ route('admin.products.index') }}" @class(['active' => request()->routeIs('admin.products.*')])>Products</a>
    <a href="{{ route('admin.shipping.index') }}" @class(['active' => request()->routeIs('admin.shipping.*')])>Shipping settings</a>
    <form method="POST" action="{{ route('admin.logout') }}">@csrf<button type="submit">Sign out</button></form>
</nav>
