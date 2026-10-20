<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ShippingOptionController extends Controller
{
    public function index(): View
    {
        $shippingOptions = ShippingOption::query()->orderBy('sort_order')->orderBy('name')->get();

        return view('admin.shipping.index', compact('shippingOptions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'charge' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);
        $key = Str::slug($data['name']);
        if (ShippingOption::query()->where('key', $key)->exists()) {
            return back()->withErrors(['name' => 'A delivery option with this name already exists.'])->withInput();
        }

        ShippingOption::query()->create([
            ...$data,
            'key' => $key,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => (int) ShippingOption::query()->max('sort_order') + 1,
        ]);

        return redirect()->route('admin.shipping.index')->with('status', 'Delivery option added.');
    }

    public function update(Request $request, ShippingOption $shippingOption): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'charge' => ['required', 'integer', 'min:0', 'max:10000'],
        ]);

        $shippingOption->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.shipping.index')->with('status', 'Delivery option updated.');
    }

    public function destroy(ShippingOption $shippingOption): RedirectResponse
    {
        $shippingOption->delete();

        return redirect()->route('admin.shipping.index')->with('status', 'Delivery option removed.');
    }
}
