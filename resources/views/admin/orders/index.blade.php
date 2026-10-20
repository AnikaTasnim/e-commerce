@extends('layouts.storefront')

@section('title', 'Manage orders · BSR')

@section('content')
<main class="admin-page">
    @include('admin.partials.navigation')
    <div class="admin-heading"><div><span class="eyebrow">STORE MANAGEMENT</span><h1>Orders</h1><p>Review customer orders and update their fulfilment status.</p></div></div>
    @if (session('status'))<div class="admin-success">{{ session('status') }}</div>@endif

    <form class="admin-product-filters" method="GET" action="{{ route('admin.orders.index') }}">
        <label>Search orders<input type="search" name="search" value="{{ request('search') }}" placeholder="Order no., customer, phone, district"></label>
        <label>Status<select name="status"><option value="">All statuses</option>@foreach (\App\Models\Order::STATUSES as $status)<option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>@endforeach</select></label>
        <button class="button button-dark" type="submit">Apply filters</button>
        <a class="text-button" href="{{ route('admin.orders.index') }}">Clear</a>
    </form>

    <section class="admin-card admin-product-table-card"><div class="admin-table-scroll"><table class="admin-product-table admin-order-table">
        <thead><tr><th>Order</th><th>Customer</th><th>Delivery to</th><th>Items</th><th>Total</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><strong>{{ $order->order_number }}</strong><small class="admin-order-date">{{ $order->created_at->format('d M Y, g:i A') }}</small></td>
                    <td><strong>{{ $order->customer_name }}</strong><small class="admin-order-date">{{ $order->phone }}</small></td>
                    <td>{{ $order->district }}<small class="admin-order-date">{{ $order->area }}</small></td>
                    <td>{{ $order->items_count }}</td>
                    <td><strong>৳{{ number_format($order->total) }}</strong></td>
                    <td><span class="admin-status admin-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td><a class="admin-order-view" href="{{ route('admin.orders.show', $order) }}">View order</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="admin-empty-row">No orders match these filters.</td></tr>
            @endforelse
        </tbody>
    </table></div><div class="admin-pagination">{{ $orders->links() }}</div></section>
</main>
@endsection
