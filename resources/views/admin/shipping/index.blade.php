@extends('layouts.admin')

@section('title', 'Manage delivery charges')
@section('section', 'Shipping settings')

@section('content')
<main class="admin-page">
    <div class="admin-heading"><div><span class="eyebrow">STORE SETTINGS</span><h1>Delivery <em>charges.</em></h1><p>Active options appear on product order forms. Changes apply to new orders only.</p></div></div>
    @if (session('status'))<div class="admin-success">{{ session('status') }}</div>@endif
    <section class="admin-card"><h2>Current delivery options</h2>
        @foreach ($shippingOptions as $option)
            <form class="shipping-admin-row" method="POST" action="{{ route('admin.shipping.update', $option) }}">
                @csrf @method('PUT')
                <label>Option name<input name="name" value="{{ $option->name }}" maxlength="80" required></label>
                <label>Charge (৳)<input name="charge" type="number" min="0" max="10000" step="1" value="{{ $option->charge }}" required></label>
                <label class="admin-active"><input type="checkbox" name="is_active" value="1" @checked($option->is_active)> Active</label>
                <button class="button button-dark icon-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4h12l3 3v13H4V4z"/><path d="M8 4v6h8V4M8 20v-6h8v6"/></svg><span>Save rate</span></button>
                <button class="text-button danger-button icon-button" type="submit" form="remove-option-{{ $option->id }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 14h10l1-14M9 7V4h6v3"/></svg><span>Remove</span></button>
            </form>
            @error('name')<span class="field-error">{{ $message }}</span>@enderror
        @endforeach
        @if ($shippingOptions->isEmpty())<p>No delivery options yet. Add one below.</p>@endif
    </section>
    <section class="admin-card add-shipping-card"><h2>Add a delivery option</h2><form class="shipping-add-form" method="POST" action="{{ route('admin.shipping.store') }}">@csrf<label>Area name<input name="name" value="{{ old('name') }}" placeholder="e.g. Within Chattogram" required maxlength="80"></label><label>Charge (৳)<input name="charge" type="number" min="0" max="10000" value="{{ old('charge') }}" required></label><label class="admin-active"><input type="checkbox" name="is_active" value="1" checked> Active</label><button class="button button-dark icon-button" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg><span>Add option</span></button></form>@error('name')<span class="field-error">{{ $message }}</span>@enderror</section>
    @foreach ($shippingOptions as $option)<form id="remove-option-{{ $option->id }}" method="POST" action="{{ route('admin.shipping.destroy', $option) }}" onsubmit="return confirm('Remove this delivery option?')">@csrf @method('DELETE')</form>@endforeach
</main>
@endsection
