<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\District;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductOrderFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_landing_page_contains_its_billing_form_and_summary(): void
    {
        [$product] = $this->makeProduct();
        ShippingOption::create(['name' => 'Inside Dhaka', 'key' => 'inside-dhaka', 'charge' => 70, 'is_active' => true, 'sort_order' => 1]);
        $district = District::create(['name' => 'Dhaka', 'slug' => 'dhaka', 'is_active' => true]);
        $district->areas()->create(['name' => 'Mirpur', 'slug' => 'mirpur', 'is_active' => true]);

        $this->get(route('products.show', $product))
            ->assertOk()
            ->assertSee('Billing details')
            ->assertSee('Order summary')
            ->assertSee('Place Order')
            ->assertDontSee('Add to bag')
            ->assertDontSee('checkout-dialog');
    }

    public function test_order_is_created_with_server_calculated_total_and_stock_is_reserved(): void
    {
        [$product, $variant] = $this->makeProduct();
        $shipping = ShippingOption::create(['name' => 'Inside Dhaka', 'key' => 'inside-dhaka', 'charge' => 70, 'is_active' => true, 'sort_order' => 1]);

        $response = $this->post(route('products.orders.store', $product), [
            'customer_name' => 'Samira Rahman',
            'phone' => '01712345678',
            'address' => '12 Lake Road',
            'district_id' => $district->id,
            'area_id' => $district->areas()->firstOrFail()->id,
            'customer_notes' => 'Call on arrival',
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'shipping_option_id' => $shipping->id,
            'payment_method' => 'cod',
            'unit_price' => 1,
            'shipping_charge' => 1,
            'total' => 1,
        ]);

        $response->assertRedirect(route('products.show', $product))->assertSessionHas('order_confirmation');
        $order = Order::query()->with('items')->sole();
        $this->assertSame('2200.00', $order->subtotal);
        $this->assertSame('400.00', $order->discount_total);
        $this->assertSame('70.00', $order->delivery_charge);
        $this->assertSame('2270.00', $order->total);
        $this->assertSame('cod', $order->payment_method);
        $this->assertSame('Dhaka', $order->district);
        $this->assertSame('Mirpur', $order->area);
        $this->assertSame(2, $order->items->sole()->quantity);
        $this->assertSame(['name' => 'Size', 'value' => 'L'], $order->items->sole()->variant_snapshot);
        $this->assertSame(18, $variant->fresh()->stock_quantity);
        $this->assertSame(18, $product->fresh()->stock_quantity);

        $this->get(route('products.show', $product))->assertOk()->assertSee($order->order_number)->assertSee('Samira Rahman');
    }

    public function test_order_rejects_quantity_above_current_stock_without_creating_an_order(): void
    {
        [$product, $variant] = $this->makeProduct(stock: 1);
        $shipping = ShippingOption::create(['name' => 'Inside Dhaka', 'key' => 'inside-dhaka', 'charge' => 70, 'is_active' => true, 'sort_order' => 1]);
        $district = District::create(['name' => 'Dhaka', 'slug' => 'dhaka', 'is_active' => true]);
        $area = $district->areas()->create(['name' => 'Mirpur', 'slug' => 'mirpur', 'is_active' => true]);

        $this->from(route('products.show', $product))->post(route('products.orders.store', $product), [
            'customer_name' => 'Samira Rahman',
            'phone' => '01712345678',
            'address' => '12 Lake Road',
            'district_id' => $district->id,
            'area_id' => $area->id,
            'product_variant_id' => $variant->id,
            'quantity' => 2,
            'shipping_option_id' => $shipping->id,
            'payment_method' => 'cod',
        ])->assertSessionHasErrors('quantity');

        $this->assertDatabaseCount('orders', 0);
        $this->assertSame(1, $variant->fresh()->stock_quantity);
    }

    public function test_shipping_options_are_admin_only_and_editable_by_an_admin(): void
    {
        $shipping = ShippingOption::create(['name' => 'Inside Dhaka', 'key' => 'inside-dhaka', 'charge' => 70, 'is_active' => true, 'sort_order' => 1]);

        $this->get(route('admin.shipping.index'))->assertRedirect(route('admin.login'));

        $admin = User::create(['name' => 'Store Admin', 'email' => 'admin@example.test', 'password' => 'a-strong-test-password', 'is_admin' => true]);
        $this->actingAs($admin)->put(route('admin.shipping.update', $shipping), [
            'name' => 'Inside Dhaka',
            'charge' => 85,
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertSame(85, $shipping->fresh()->charge);
    }

    private function makeProduct(int $stock = 20): array
    {
        $product = Product::create([
            'name' => 'Everyday Shirt',
            'slug' => 'everyday-shirt',
            'sku' => 'SHIRT-001',
            'category' => 'MEN',
            'description' => 'A soft everyday shirt.',
            'short_description' => 'A comfortable everyday favorite.',
            'regular_price' => 1200,
            'discount_price' => 1000,
            'stock_quantity' => $stock,
            'status' => 'active',
            'featured' => true,
            'main_image' => 'https://images.example.test/shirt.jpg',
            'material' => 'Cotton',
            'quality_note' => 'Soft and breathable.',
        ]);
        $variant = ProductVariant::create([
            'product_id' => $product->id,
            'name' => 'Size',
            'value' => 'L',
            'sku' => 'SHIRT-001-L',
            'stock_quantity' => $stock,
            'price_adjustment' => 100,
            'is_active' => true,
            'sort_order' => 1,
        ]);

        return [$product, $variant];
    }
}
