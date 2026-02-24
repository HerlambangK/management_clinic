<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::withCount('services')
            ->ordered()
            ->paginate(10);

        return view('service-categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('service-categories.create');
    }

    public function store(ServiceCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['sort_order'] = ServiceCategory::max('sort_order') + 1;

        ServiceCategory::create($data);

        return redirect()->route('service-categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('service-categories.edit', compact('serviceCategory'));
    }

    public function update(ServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update($request->validated());

        return redirect()->route('service-categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        if ($serviceCategory->services()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki layanan.');
        }

        $serviceCategory->delete();

        return redirect()->route('service-categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }

    public function reorder(Request $request): RedirectResponse
    {
        $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'integer', 'exists:service_categories,id'],
        ]);

        foreach ($request->categories as $index => $id) {
            ServiceCategory::where('id', $id)->update(['sort_order' => $index]);
        }

        return back()->with('success', 'Urutan kategori berhasil diperbarui.');
    }
}
