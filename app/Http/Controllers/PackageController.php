<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Models\Package;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = Package::with('service')->withCount('customerPackages');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $packages = $query->ordered()->paginate(15)->withQueryString();

        return view('packages.index', compact('packages'));
    }

    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('packages.create', compact('services'));
    }

    public function store(PackageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        Package::create($data);

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dibuat.');
    }

    public function show(Package $package): View
    {
        $package->load(['service', 'customerPackages' => function ($query) {
            $query->with(['customer', 'seller'])
                ->latest()
                ->limit(10);
        }]);

        $stats = [
            'total_sold' => $package->customerPackages()->count(),
            'active' => $package->customerPackages()->where('status', 'active')->count(),
            'completed' => $package->customerPackages()->where('status', 'completed')->count(),
            'total_revenue' => $package->customerPackages()->sum('price_paid'),
        ];

        return view('packages.show', compact('package', 'stats'));
    }

    public function edit(Package $package): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('packages.edit', compact('package', 'services'));
    }

    public function update(PackageRequest $request, Package $package): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active', true);

        $package->update($data);

        return redirect()->route('packages.show', $package)
            ->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        if ($package->customerPackages()->where('status', 'active')->exists()) {
            return back()->with('error', 'Paket tidak dapat dihapus karena masih ada pembelian aktif.');
        }

        $package->delete();

        return redirect()->route('packages.index')
            ->with('success', 'Paket berhasil dihapus.');
    }

    public function toggleActive(Package $package): RedirectResponse
    {
        $package->update(['is_active' => ! $package->is_active]);

        $status = $package->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Paket berhasil {$status}.");
    }
}
