<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('sku', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('stock')) {
            if ($request->stock === 'low') {
                $query->lowStock();
            } elseif ($request->stock === 'out') {
                $query->where('track_stock', true)->where('stock', '<=', 0);
            }
        }

        $products = $query->latest()->paginate(10)->withQueryString();
        $categories = ProductCategory::active()->ordered()->get();

        $lowStockCount = Product::lowStock()->count();

        return view('products.index', compact('products', 'categories', 'lowStockCount'));
    }

    public function create(): View
    {
        $categories = ProductCategory::active()->ordered()->get();

        return view('products.create', compact('categories'));
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', __('product.created'));
    }

    public function show(Product $product): View
    {
        $product->load('category');

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $categories = ProductCategory::active()->ordered()->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', __('product.updated'));
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->transactionItems()->count() > 0) {
            return back()->with('error', __('product.has_transactions'));
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', __('product.deleted'));
    }

    public function toggleActive(Product $product): RedirectResponse
    {
        $product->update(['is_active' => ! $product->is_active]);

        $message = $product->is_active ? __('product.activated') : __('product.deactivated');

        return back()->with('success', $message);
    }

    public function adjustStock(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'adjustment' => ['required', 'integer'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $adjustment = (int) $request->adjustment;

        if ($adjustment > 0) {
            $product->increaseStock($adjustment);
        } else {
            $newStock = $product->stock + $adjustment;
            if ($newStock < 0) {
                return back()->with('error', __('product.stock_insufficient'));
            }
            $product->update(['stock' => $newStock]);
        }

        return back()->with('success', __('product.stock_adjusted'));
    }
}
