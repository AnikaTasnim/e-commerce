@extends('layouts.admin')

@section('title', 'Sales reports | BSR Admin')
@section('section', 'Sales reports')

@section('content')
<div class="admin-heading">
    <div>
        <div class="admin-eyebrow">STORE PERFORMANCE</div>
        <h1>Sales reports</h1>
        <p>Review completed sales by day, month, or year.</p>
    </div>
    <a class="button button-dark" href="{{ route('admin.reports.sales.export', ['period' => $periodType, 'date' => $dateValue, 'month' => $monthValue, 'year' => $yearValue]) }}">Download Excel</a>
</div>

<form class="admin-card report-filters" method="GET" action="{{ route('admin.reports.sales') }}">
    <label>Report period
        <select name="period" id="report-period">
            <option value="daily" @selected($periodType === 'daily')>Daily</option>
            <option value="monthly" @selected($periodType === 'monthly')>Monthly</option>
            <option value="yearly" @selected($periodType === 'yearly')>Yearly</option>
        </select>
    </label>
    <label data-period-field="daily">Date
        <input type="date" name="date" value="{{ $dateValue }}">
    </label>
    <label data-period-field="monthly">Month
        <input type="month" name="month" value="{{ $monthValue }}">
    </label>
    <label data-period-field="yearly">Year
        <input type="number" name="year" min="2000" max="2100" value="{{ $yearValue }}">
    </label>
    <button class="button button-dark" type="submit">Generate report</button>
</form>

<div class="report-period-caption">{{ $periodLabel }} <span>· Cancelled orders are excluded</span></div>

<section class="report-summary" aria-label="Sales totals">
    <article class="metric-card"><span class="metric-label">Orders</span><strong>{{ number_format($summary->order_count) }}</strong><small>Non-cancelled orders</small></article>
    <article class="metric-card"><span class="metric-label">Product subtotal</span><strong>৳{{ number_format((float) $summary->product_subtotal, 2) }}</strong><small>Before delivery charges</small></article>
    <article class="metric-card"><span class="metric-label">Shipping charges</span><strong>৳{{ number_format((float) $summary->shipping_total, 2) }}</strong><small>Delivery fees collected</small></article>
    <article class="metric-card"><span class="metric-label">Total sales</span><strong>৳{{ number_format((float) $summary->sales_total, 2) }}</strong><small>Product subtotal plus shipping</small></article>
</section>

<section class="admin-card admin-product-table-card report-table-card">
    <div class="report-table-heading"><div><h2>{{ $periodType === 'daily' ? 'Orders for this day' : ($periodType === 'monthly' ? 'Daily breakdown' : 'Monthly breakdown') }}</h2><p>{{ $periodType === 'daily' ? 'Individual order totals' : 'Sales totals grouped by period' }}</p></div></div>
    <div class="admin-table-scroll">
        <table class="admin-product-table">
            @if($periodType === 'daily')
                <thead><tr><th>Order</th><th>Customer</th><th>Time</th><th>Subtotal</th><th>Shipping</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                @forelse($rows as $order)
                    <tr>
                        <td><a class="report-order-link" href="{{ route('admin.orders.show', $order) }}">{{ $order->order_number }}</a></td>
                        <td>{{ $order->customer_name }}<small class="report-secondary">{{ $order->phone }}</small></td>
                        <td>{{ $order->created_at->timezone('Asia/Dhaka')->format('g:i A') }}</td>
                        <td>৳{{ number_format((float) $order->subtotal, 2) }}</td>
                        <td>৳{{ number_format((float) $order->delivery_charge, 2) }}</td>
                        <td><strong>৳{{ number_format((float) $order->total, 2) }}</strong></td>
                        <td><span class="admin-status admin-status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                @empty
                    <tr><td class="admin-empty-row" colspan="7">No sales found for {{ $periodLabel }}.</td></tr>
                @endforelse
                </tbody>
            @else
                <thead><tr><th>{{ $periodType === 'monthly' ? 'Date' : 'Month' }}</th><th>Orders</th><th>Product subtotal</th><th>Shipping</th><th>Total sales</th></tr></thead>
                <tbody>
                @forelse($rows as $row)
                    <tr><td>{{ $periodType === 'monthly' ? \Carbon\CarbonImmutable::parse($row->report_period)->format('d M Y') : \Carbon\CarbonImmutable::createFromFormat('!Y-m', $row->report_period)->format('F Y') }}</td><td>{{ number_format($row->order_count) }}</td><td>৳{{ number_format((float) $row->product_subtotal, 2) }}</td><td>৳{{ number_format((float) $row->shipping_total, 2) }}</td><td><strong>৳{{ number_format((float) $row->sales_total, 2) }}</strong></td></tr>
                @empty
                    <tr><td class="admin-empty-row" colspan="5">No sales found for {{ $periodLabel }}.</td></tr>
                @endforelse
                </tbody>
            @endif
        </table>
    </div>
</section>
@endsection

@push('scripts')
<script>
(() => {
    const period = document.getElementById('report-period');
    const updateFields = () => document.querySelectorAll('[data-period-field]').forEach((field) => {
        const visible = field.dataset.periodField === period.value;
        field.hidden = !visible;
        const input = field.querySelector('input');
        input.disabled = !visible;
        input.required = visible;
    });
    period.addEventListener('change', updateFields);
    updateFields();
})();
</script>
@endpush
