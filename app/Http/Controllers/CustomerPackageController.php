<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerPackageRequest;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPackageController extends Controller
{
    public function index(Request $request): View
    {
        $query = CustomerPackage::with(['customer', 'package', 'seller']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $customerPackages = $query->latest()->paginate(15)->withQueryString();

        return view('customer-packages.index', compact('customerPackages'));
    }

    public function create(Request $request): View
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::active()->ordered()->get();
        $selectedCustomerId = $request->get('customer_id');

        return view('customer-packages.create', compact('customers', 'packages', 'selectedCustomerId'));
    }

    public function store(CustomerPackageRequest $request): RedirectResponse
    {
        $package = Package::findOrFail($request->package_id);

        $customerPackage = CustomerPackage::create([
            'customer_id' => $request->customer_id,
            'package_id' => $request->package_id,
            'sold_by' => auth()->id(),
            'price_paid' => $request->price_paid,
            'sessions_total' => $package->total_sessions,
            'sessions_used' => 0,
            'purchased_at' => $request->purchased_at,
            'expires_at' => date('Y-m-d', strtotime($request->purchased_at." + {$package->validity_days} days")),
            'status' => 'active',
            'notes' => $request->notes,
        ]);

        // Update customer total spent
        $customer = Customer::find($request->customer_id);
        $customer->increment('total_spent', $request->price_paid);

        return redirect()->route('customer-packages.show', $customerPackage)
            ->with('success', 'Pembelian paket berhasil dicatat.');
    }

    public function show(CustomerPackage $customerPackage): View
    {
        $customerPackage->load(['customer', 'package.service', 'seller', 'usages.usedByStaff', 'usages.appointment.service']);

        return view('customer-packages.show', compact('customerPackage'));
    }

    public function useSession(Request $request, CustomerPackage $customerPackage): RedirectResponse
    {
        if (! $customerPackage->is_usable) {
            return back()->with('error', 'Paket tidak dapat digunakan. Pastikan paket masih aktif dan belum kadaluarsa.');
        }

        $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $customerPackage->useSession(
            appointmentId: $request->appointment_id,
            usedBy: auth()->id(),
            notes: $request->notes
        );

        return back()->with('success', 'Sesi paket berhasil digunakan.');
    }

    public function cancel(CustomerPackage $customerPackage): RedirectResponse
    {
        if ($customerPackage->status !== 'active') {
            return back()->with('error', 'Hanya paket aktif yang dapat dibatalkan.');
        }

        $customerPackage->update(['status' => 'cancelled']);

        return back()->with('success', 'Paket berhasil dibatalkan.');
    }

    public function getPackageDetails(Package $package): JsonResponse
    {
        return response()->json([
            'id' => $package->id,
            'name' => $package->name,
            'total_sessions' => $package->total_sessions,
            'package_price' => $package->package_price,
            'validity_days' => $package->validity_days,
            'formatted_price' => $package->formatted_package_price,
            'service' => $package->service ? [
                'id' => $package->service->id,
                'name' => $package->service->name,
            ] : null,
        ]);
    }

    public function getCustomerPackages(Customer $customer): JsonResponse
    {
        $packages = $customer->packages()
            ->usable()
            ->with('package.service')
            ->get()
            ->map(function ($cp) {
                return [
                    'id' => $cp->id,
                    'package_name' => $cp->package->name,
                    'service_name' => $cp->package->service?->name,
                    'sessions_remaining' => $cp->sessions_remaining,
                    'sessions_total' => $cp->sessions_total,
                    'expires_at' => $cp->expires_at->format('d M Y'),
                    'days_remaining' => $cp->days_remaining,
                ];
            });

        return response()->json(['packages' => $packages]);
    }
}
