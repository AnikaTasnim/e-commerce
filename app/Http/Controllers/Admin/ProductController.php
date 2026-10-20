<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->withCount(['variants', 'images'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim($request->string('search')->toString());
                $query->where(fn ($query) => $query->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%"));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', (string) $request->input('status')))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        $product = new Product;
        $variants = collect([['name' => 'Size', 'value' => '', 'sku' => '', 'stock_quantity' => 0, 'price_adjustment' => 0, 'is_active' => true]]);

        return view('admin.products.form', compact('product', 'variants'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if (! $request->hasFile('main_image_upload') && blank($data['main_image'] ?? null)) {
            throw ValidationException::withMessages(['main_image_upload' => 'Add a main image URL or upload an image.']);
        }

        DB::transaction(function () use ($request, $data) {
            $mainImage = $this->mainImagePath($request, $data);
            $product = Product::query()->create([...$this->productAttributes($data, $request), 'main_image' => $mainImage]);
            $this->savePrimaryImage($product, $mainImage);
            $this->addGalleryImages($product, $request, $data);
            $this->syncVariants($product, $data['variants'] ?? []);
        });

        return redirect()->route('admin.products.index')->with('status', 'Product created.');
    }

    public function edit(Product $product): View
    {
        $product->load(['images' => fn ($query) => $query->orderBy('sort_order'), 'variants' => fn ($query) => $query->orderBy('sort_order')]);

        return view('admin.products.form', ['product' => $product, 'variants' => $product->variants]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validatedData($request, $product);

        DB::transaction(function () use ($request, $data, $product) {
            $oldMainImage = $product->main_image;
            $mainImage = $this->mainImagePath($request, $data, $product);
            $product->update([...$this->productAttributes($data, $request, $product), 'main_image' => $mainImage]);
            $this->savePrimaryImage($product, $mainImage);

            if ($mainImage !== $oldMainImage) {
                $this->deleteLocalImage($oldMainImage);
            }

            foreach ($data['remove_image_ids'] ?? [] as $imageId) {
                $image = $product->images()->whereKey($imageId)->where('is_primary', false)->first();
                if ($image) {
                    $this->deleteLocalImage($image->image_path);
                    $image->delete();
                }
            }

            $this->addGalleryImages($product, $request, $data);
            $this->syncVariants($product, $data['variants'] ?? []);
        });

        return redirect()->route('admin.products.edit', $product)->with('status', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $paths = $product->images()->pluck('image_path')->push($product->main_image)->unique();
        $product->delete();
        $paths->each(fn ($path) => $this->deleteLocalImage($path));

        return redirect()->route('admin.products.index')->with('status', 'Product deleted.');
    }

    private function validatedData(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', Rule::unique('products', 'slug')->ignore($product?->id)],
            'sku' => ['required', 'string', 'max:100', Rule::unique('products', 'sku')->ignore($product?->id)],
            'category' => ['required', 'string', 'max:100'],
            'short_description' => ['required', 'string', 'max:500'],
            'description' => ['required', 'string', 'max:10000'],
            'material' => ['nullable', 'string', 'max:180'],
            'quality_note' => ['nullable', 'string', 'max:500'],
            'features_text' => ['nullable', 'string', 'max:5000'],
            'regular_price' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:regular_price'],
            'stock_quantity' => ['required', 'integer', 'min:0', 'max:1000000'],
            'status' => ['required', Rule::in(['active', 'draft'])],
            'featured' => ['nullable', 'boolean'],
            'main_image' => ['nullable', 'url', 'max:255'],
            'main_image_upload' => ['nullable', 'image', 'max:5120'],
            'gallery_urls' => ['nullable', 'string', 'max:5000'],
            'gallery_uploads' => ['nullable', 'array', 'max:10'],
            'gallery_uploads.*' => ['image', 'max:5120'],
            'remove_image_ids' => ['nullable', 'array'],
            'remove_image_ids.*' => ['integer'],
            'variants' => ['nullable', 'array', 'max:30'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.name' => ['nullable', 'string', 'max:80'],
            'variants.*.value' => ['nullable', 'string', 'max:100'],
            'variants.*.sku' => ['nullable', 'string', 'max:100'],
            'variants.*.stock_quantity' => ['nullable', 'integer', 'min:0', 'max:1000000'],
            'variants.*.price_adjustment' => ['nullable', 'numeric', 'min:-999999', 'max:999999'],
            'variants.*.is_active' => ['nullable', 'boolean'],
        ]);

        foreach ($data['variants'] ?? [] as $index => $variant) {
            if (filled($variant['id'] ?? null) && ! $product?->variants()->whereKey($variant['id'])->exists()) {
                throw ValidationException::withMessages(["variants.{$index}.id" => 'This variant does not belong to this product.']);
            }

            if (filled($variant['name'] ?? null) xor filled($variant['value'] ?? null)) {
                throw ValidationException::withMessages(["variants.{$index}.value" => 'Enter both an option name and value, or leave the row blank.']);
            }

            if (blank($variant['name'] ?? null) && blank($variant['value'] ?? null)) {
                continue;
            }

            $existingVariant = $product?->variants()->where('name', $variant['name'])->where('value', $variant['value']);
            if ($existingVariant && filled($variant['id'] ?? null)) {
                $existingVariant->where('id', '!=', $variant['id']);
            }
            if ($existingVariant?->exists()) {
                throw ValidationException::withMessages(["variants.{$index}.value" => 'This option already exists for the product.']);
            }

            if (filled($variant['sku'] ?? null)) {
                $skuQuery = ProductVariant::query()->where('sku', $variant['sku']);
                if (filled($variant['id'] ?? null)) {
                    $skuQuery->where('id', '!=', $variant['id']);
                }
                if ($skuQuery->exists()) {
                    throw ValidationException::withMessages(["variants.{$index}.sku" => 'This variant SKU is already in use.']);
                }
            }
        }
        $variantSkus = collect($data['variants'] ?? [])->pluck('sku')->filter()->map(fn ($sku) => Str::lower($sku));
        if ($variantSkus->duplicates()->isNotEmpty()) {
            throw ValidationException::withMessages(['variants' => 'Each variant SKU must be unique.']);
        }

        $galleryUrls = collect(preg_split('/\r\n|\r|\n/', $data['gallery_urls'] ?? '') ?: [])
            ->map(fn ($url) => trim($url))->filter()->values()->all();
        validator(['gallery_urls' => $galleryUrls], ['gallery_urls.*' => ['url', 'max:255']])->validate();
        $data['parsed_gallery_urls'] = $galleryUrls;

        return $data;
    }

    private function productAttributes(array $data, Request $request, ?Product $product = null): array
    {
        $slug = filled($data['slug'] ?? null) ? Str::slug($data['slug']) : Str::slug($data['name']);
        $baseSlug = $slug;
        $suffix = 2;
        while (Product::query()->where('slug', $slug)->when($product, fn ($query) => $query->where('id', '!=', $product->id))->exists()) {
            $slug = $baseSlug.'-'.$suffix++;
        }
        $features = collect(preg_split('/\r\n|\r|\n/', $data['features_text'] ?? '') ?: [])
            ->map(fn ($feature) => trim($feature))->filter()->values()->all();

        return [
            ...Arr::only($data, ['name', 'sku', 'category', 'short_description', 'description', 'material', 'quality_note', 'regular_price', 'discount_price', 'stock_quantity', 'status']),
            'slug' => $slug,
            'features' => $features,
            'featured' => $request->boolean('featured'),
        ];
    }

    private function mainImagePath(Request $request, array $data, ?Product $product = null): string
    {
        if ($request->hasFile('main_image_upload')) {
            return 'storage/'.$request->file('main_image_upload')->store('products', 'public');
        }

        return $data['main_image'] ?? $product?->main_image ?? '';
    }

    private function savePrimaryImage(Product $product, string $path): void
    {
        $primary = $product->images()->where('is_primary', true)->first();
        if ($primary) {
            $primary->update(['image_path' => $path, 'alt_text' => $product->name]);
        } else {
            $product->images()->create(['image_path' => $path, 'alt_text' => $product->name, 'is_primary' => true, 'sort_order' => 0]);
        }
    }

    private function addGalleryImages(Product $product, Request $request, array $data): void
    {
        $sortOrder = (int) $product->images()->where('is_primary', false)->max('sort_order') + 1;
        foreach ($data['parsed_gallery_urls'] ?? [] as $url) {
            $product->images()->create(['image_path' => $url, 'alt_text' => $product->name, 'is_primary' => false, 'sort_order' => $sortOrder++]);
        }
        foreach ($request->file('gallery_uploads', []) as $file) {
            $product->images()->create([
                'image_path' => 'storage/'.$file->store('products/gallery', 'public'),
                'alt_text' => $product->name,
                'is_primary' => false,
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    private function syncVariants(Product $product, array $variants): void
    {
        $keptIds = [];
        foreach ($variants as $sortOrder => $variantData) {
            if (blank($variantData['name'] ?? null) && blank($variantData['value'] ?? null)) {
                continue;
            }

            $attributes = [
                'name' => $variantData['name'],
                'value' => $variantData['value'],
                'sku' => blank($variantData['sku'] ?? null) ? null : $variantData['sku'],
                'stock_quantity' => (int) ($variantData['stock_quantity'] ?? 0),
                'price_adjustment' => (float) ($variantData['price_adjustment'] ?? 0),
                'is_active' => filter_var($variantData['is_active'] ?? false, FILTER_VALIDATE_BOOLEAN),
                'sort_order' => $sortOrder,
            ];

            if (filled($variantData['id'] ?? null)) {
                $variant = $product->variants()->whereKey($variantData['id'])->firstOrFail();
                $variant->update($attributes);
            } else {
                $variant = $product->variants()->create($attributes);
            }
            $keptIds[] = $variant->id;
        }

        $product->variants()->whereNotIn('id', $keptIds ?: [0])->get()->each(function (ProductVariant $variant) {
            if ($variant->orderItems()->exists()) {
                $variant->update(['is_active' => false]);
            } else {
                $variant->delete();
            }
        });
    }

    private function deleteLocalImage(?string $path): void
    {
        if ($path && Str::startsWith($path, 'storage/products/')) {
            Storage::disk('public')->delete(Str::after($path, 'storage/'));
        }
    }
}
