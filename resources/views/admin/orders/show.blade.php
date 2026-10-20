@extends('layouts.storefront')

@section('title', 'Order '.$order->order_number.' · BSR')

@section('content')
<main class="admin-page admin-order-detail-page">
    @include('admin.partials.navigation')
    <div class="admin-heading"><div><span class="eyebrow">ORDER DETAILS</span><h1>{{ $order->order_number }}</h1><p>Placed {{ $order->created_at->format('d M Y, g:i A') }}</p></div><a class="text-button" href="{{ route('admin.orders.index') }}">Back to orders</a></div>
    @if (session('status'))<div class="admin-success">{{ session('status') }}</div>@endif

    <div class="admin-order-detail-grid">
        <section class="admin-card admin-order-items-card"><h2>Ordered products</h2>
            <div class="admin-table-scroll"><table class="admin-product-table"><thead><tr><th>Product / option</th><th>Unit price</th><th>Qty</th><th>Line total</th></tr></thead><tbody>
                @foreach ($order->items as $item)
                    @php($variantSnapshot = $item->variant_snapshot ?? [])
                    <tr><td><strong>{{ $item->product_name }}</strong><small class="admin-order-date">{{ $variantSnapshot['name'] ?? 'Option' }}: {{ $variantSnapshot['value'] ?? '—' }}@if (! $item->product) · Removed from catalog @endif</small></td><td>৳{{ number_format($item->unit_price) }}</td><td>{{ $item->quantity }}</td><td><strong>৳{{ number_format($item->line_total) }}</strong></td></tr>
                @endforeach
            </tbody></table></div>
            <dl class="admin-order-totals"><div><dt>Subtotal</dt><dd>৳{{ number_format($order->subtotal) }}</dd></div>@if ((float) $order->discount_total > 0)<div><dt>Discount</dt><dd>−৳{{ number_format($order->discount_total) }}</dd></div>@endif<div><dt>Shipping · {{ $order->shipping_name_snapshot }}</dt><dd>৳{{ number_format($order->delivery_charge) }}</dd></div><div class="admin-order-grand-total"><dt>Total</dt><dd>৳{{ number_format($order->total) }}</dd></div></dl>
        </section>

        <aside class="admin-order-side-column">
            <section class="admin-card"><h2>Customer & delivery</h2><dl class="admin-order-info"><div><dt>Name</dt><dd>{{ $order->customer_name }}</dd></div><div><dt>Mobile</dt><dd><a href="tel:{{ $order->phone }}">{{ $order->phone }}</a></dd></div><div><dt>Address</dt><dd>{{ $order->address }}</dd></div><div><dt>District</dt><dd>{{ $order->district }}</dd></div><div><dt>Area</dt><dd>{{ $order->area ?: '—' }}</dd></div><div><dt>Notes</dt><dd>{{ $order->customer_notes ?: '—' }}</dd></div><div><dt>Payment</dt><dd>{{ $order->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst($order->payment_method) }}</dd></div></dl></section>
            <section class="admin-card"><h2>Order status</h2><p><span class="admin-status admin-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></p><form method="POST" action="{{ route('admin.orders.status.update', $order) }}" class="admin-order-status-form">@csrf @method('PATCH')<label for="order-status">Update status</label><select name="status" id="order-status" required>@foreach ($statuses as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select><button class="button button-dark" type="submit">Save status</button></form></section>
        </aside>
    </div>
</main>
@endsection
