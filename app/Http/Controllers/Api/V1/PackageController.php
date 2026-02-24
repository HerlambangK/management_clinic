<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerPackageResource;
use App\Http\Resources\PackageResource;
use App\Http\Resources\PackageUsageResource;
use App\Models\Customer;
use App\Models\CustomerPackage;
use App\Models\Package;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PackageController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Package::query()->active()->ordered()->with('service');

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        return PackageResource::collection($query->get());
    }

    public function show(Package $package): JsonResponse
    {
        $package->load('service');

        return response()->json([
            'data' => new PackageResource($package),
        ]);
    }

    public function customerPackages(Request $request): AnonymousResourceCollection
    {
        $query = CustomerPackage::query()->with(['customer', 'package.service', 'seller']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->boolean('active_only', false)) {
            $query->usable();
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $perPage = $request->integer('per_page', 15);
        $packages = $query->latest()->paginate($perPage);

        return CustomerPackageResource::collection($packages);
    }

    public function showCustomerPackage(CustomerPackage $customerPackage): JsonResponse
    {
        $customerPackage->load(['customer', 'package.service', 'seller', 'usages']);

        return response()->json([
            'data' => new CustomerPackageResource($customerPackage),
        ]);
    }

    public function storeCustomerPackage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'package_id' => ['required', 'exists:packages,id'],
            'price_paid' => ['nullable', 'numeric', 'min:0'],
            'purchased_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $purchasedAt = $validated['purchased_at'] ?? today()->toDateString();

        $customerPackage = CustomerPackage::create([
            'customer_id' => $validated['customer_id'],
            'package_id' => $validated['package_id'],
            'sold_by' => auth()->id(),
            'price_paid' => $validated['price_paid'] ?? $package->package_price,
            'sessions_total' => $package->total_sessions,
            'sessions_used' => 0,
            'purchased_at' => $purchasedAt,
            'expires_at' => date('Y-m-d', strtotime($purchasedAt." + {$package->validity_days} days")),
            'status' => 'active',
            'notes' => $validated['notes'] ?? null,
        ]);

        $customer = Customer::find($validated['customer_id']);
        $customer->increment('total_spent', $customerPackage->price_paid);

        $customerPackage->load(['customer', 'package.service', 'seller']);

        return response()->json([
            'data' => new CustomerPackageResource($customerPackage),
        ], 201);
    }

    public function useSession(Request $request, CustomerPackage $customerPackage): JsonResponse
    {
        $validated = $request->validate([
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        if (! $customerPackage->is_usable) {
            return response()->json([
                'message' => 'Paket ini tidak dapat digunakan.',
                'errors' => ['customer_package' => ['Paket sudah habis, kadaluarsa, atau tidak aktif.']],
            ], 422);
        }

        $usage = $customerPackage->useSession(
            $validated['appointment_id'] ?? null,
            auth()->id(),
            $validated['notes'] ?? null,
        );

        $usage->load('usedByStaff');
        $customerPackage->refresh()->load(['customer', 'package.service', 'seller']);

        return response()->json([
            'data' => new CustomerPackageResource($customerPackage),
            'usage' => new PackageUsageResource($usage),
        ]);
    }

    public function usablePackages(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'service_id' => ['nullable', 'exists:services,id'],
        ]);

        $query = CustomerPackage::query()
            ->usable()
            ->where('customer_id', $validated['customer_id'])
            ->with(['customer', 'package.service', 'seller']);

        if ($request->filled('service_id')) {
            $query->whereHas('package', function ($q) use ($validated) {
                $q->where('service_id', $validated['service_id']);
            });
        }

        return CustomerPackageResource::collection($query->get());
    }
}
