@extends('layouts.admin')
@section('title', 'Dashboard � BSR Admin')
@section('section', 'Dashboard')
@section('content')
<div class="admin-heading"><div><span class="admin-eyebrow">STORE OVERVIEW</span><h1>Dashboard</h1><p>Welcome back. Here�s what�s happening in your store.</p></div><a class="button button-dark" href="{{ route('admin.products.create') }}">+ Add product</a></div>
<div class="dashboard-metrics">
    <a class="metric-card" href="{{ route('admin.products.index') }}"><span class="metric-icon metric-orange">?</span><span class="metric-label">Total products</span><strong>{{ number_format($metrics['products']) }}</strong><small>{{ number_format($metrics['active_products']) }} active in storefront</small></a>
    <a class="metric-card" href="{{ route('admin.orders.index') }}"><span class="metric-icon metric-blue">?</span><span class="metric-label">Total orders</span><strong>{{ number_format($metrics['orders']) }}</strong><small>{{ number_format($metrics['pending_orders']) }} awaiting confirmation</small></a>
    <div class="metric-card"><span class="metric-icon metric-green">?</span><span class="metric-label">Order revenue</span><strong>?{{ number_format($metrics['revenue']) }}</strong><small>Excludes cancelled orders</small></div>
    <div class="metric-card"><span class="metric-icon metric-purple">?</span><span class="metric-label">Customers</span><strong>{{ number_format($metrics['customers']) }}</strong><small>Unique customer numbers</small></div>
</div>
<section class="admin-card dashboard-recent"><div class="dashboard-card-heading"><div><h2>Recent orders</h2><p>Latest customer orders and fulfillment status.</p></div><a href="{{ route('admin.orders.index') }}" class="admin-order-view">View all orders ?</a></div>
<div class="admin-table-scroll"><table class="admin-product-table"><thead><tr><th>Order</th><th>Customer</th><th>Date</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead><tbody>
@forelse($recentOrders as $order)<tr><td><strong>{{ $order->order_number }}</strong></td><td><strong>{{ $order->customer_name }}</strong><small class="admin-order-date">{{ $order->phone }}</small></td><td>{{ $order->created_at->format('d M Y') }}</td><td>{{ $order->items_count }}</td><td><strong>?{{ number_format($order->total) }}</strong></td><td><span class="admin-status admin-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td><td><a class="admin-order-view" href="{{ route('admin.orders.show', $order) }}">Details</a></td></tr>
@empty<tr><td colspan="7" class="admin-empty-row">No orders have been placed yet.</td></tr>@endforelse
</tbody></table></div></section>
<div class="dashboard-shortcuts"><a href="{{ route('admin.products.index') }}"><span>?</span><div><strong>Manage products</strong><small>Update prices, inventory, images, and variants</small></div><b>?</b></a><a href="{{ route('admin.shipping.index') }}"><span>?</span><div><strong>Shipping settings</strong><small>Manage delivery options and charges</small></div><b>?</b></a></div>
@endsection
