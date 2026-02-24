<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::withCount('products')
            ->ordered()
            ->paginate(10);

        return view('product-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('product-categories.create');
    }

    public function store(ProductCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = ProductCategory::max('sort_order') + 1;

        ProductCategory::create($data);

        return redirect()->route('product-categories.index')
            ->with('success', __('product.category_created'));
    }

    public function edit(ProductCategory $productCategory): View
    {
        return view('product-categories.edit', compact('productCategory'));
    }

    public function update(ProductCategoryRequest $request, ProductCategory $productCategory): RedirectResponse
    {
        $productCategory->update($request->validated());

        return redirect()->route('product-categories.index')
            ->with('success', __('product.category_updated'));
    }

    public function destroy(ProductCategory $productCategory): RedirectResponse
    {
        if ($productCategory->products()->count() > 0) {
            return back()->with('error', __('product.category_has_products'));
        }

        $productCategory->delete();

        return redirect()->route('product-categories.index')
            ->with('success', __('product.category_deleted'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'integer', 'exists:product_categories,id'],
        ]);

        foreach ($request->categories as $index => $id) {
            ProductCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', __('product.category_reordered'));
    }
}
