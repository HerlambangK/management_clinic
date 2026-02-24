<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    /**
     * Get all product categories
     */
    public function categories(Request $request): AnonymousResourceCollection
    {
        $query = ProductCategory::query()
            ->active()
            ->ordered();

        if ($request->boolean('with_products')) {
            $query->with(['products' => fn ($q) => $q->active()->inStock()]);
        }

        if ($request->boolean('with_count')) {
            $query->withCount(['products' => fn ($q) => $q->active()]);
        }

        return ProductCategoryResource::collection($query->get());
    }

    /**
     * Get single category with products
     */
    public function showCategory(ProductCategory $productCategory): ProductCategoryResource
    {
        $productCategory->load(['products' => fn ($q) => $q->active()->inStock()]);

        return new ProductCategoryResource($productCategory);
    }

    /**
     * Get all products
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Product::query()
            ->active()
            ->with('category');

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('sku', 'like', "%{$request->search}%")
                    ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->boolean('in_stock_only', true)) {
            $query->inStock();
        }

        $products = $query->orderBy('name')->paginate($request->per_page ?? 20);

        return ProductResource::collection($products);
    }

    /**
     * Get single product
     */
    public function show(Product $product): ProductResource
    {
        $product->load('category');

        return new ProductResource($product);
    }
}
