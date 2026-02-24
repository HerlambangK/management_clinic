<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Http\Resources\CustomerPackageResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\TreatmentRecordResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CustomerController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        $perPage = $request->integer('per_page', 15);
        $customers = $query->latest()->paginate($perPage);

        return CustomerResource::collection($customers);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:customers,phone'],
            'email' => ['nullable', 'email', 'max:255'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'skin_type' => ['nullable', 'in:normal,oily,dry,combination,sensitive'],
            'skin_concerns' => ['nullable', 'array'],
            'skin_concerns.*' => ['string'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'message' => 'Customer berhasil ditambahkan',
            'data' => new CustomerResource($customer),
        ], 201);
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json([
            'data' => new CustomerResource($customer),
        ]);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'phone' => ['sometimes', 'required', 'string', 'max:20', 'unique:customers,phone,'.$customer->id],
            'email' => ['nullable', 'email', 'max:255'],
            'birthdate' => ['nullable', 'date', 'before:today'],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string', 'max:500'],
            'skin_type' => ['nullable', 'in:normal,oily,dry,combination,sensitive'],
            'skin_concerns' => ['nullable', 'array'],
            'skin_concerns.*' => ['string'],
            'allergies' => ['nullable', 'string', 'max:500'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $customer->update($validated);

        return response()->json([
            'message' => 'Customer berhasil diperbarui',
            'data' => new CustomerResource($customer),
        ]);
    }

    public function destroy(Customer $customer): JsonResponse
    {
        $customer->delete();

        return response()->json([
            'message' => 'Customer berhasil dihapus',
        ]);
    }

    public function stats(Customer $customer): JsonResponse
    {
        return response()->json([
            'data' => [
                'total_visits' => $customer->total_visits,
                'total_spent' => (float) $customer->total_spent,
                'formatted_total_spent' => $customer->formatted_total_spent,
                'last_visit' => $customer->last_visit?->format('Y-m-d'),
                'member_since' => $customer->created_at?->format('Y-m-d'),
                'active_packages_count' => $customer->activePackages()->count(),
            ],
        ]);
    }

    public function treatments(Request $request, Customer $customer): AnonymousResourceCollection
    {
        $perPage = $request->integer('per_page', 15);
        $treatments = $customer->treatmentRecords()
            ->with(['appointment.service', 'staff'])
            ->latest()
            ->paginate($perPage);

        return TreatmentRecordResource::collection($treatments);
    }

    public function packages(Request $request, Customer $customer): AnonymousResourceCollection
    {
        $query = $customer->packages()->with(['package.service', 'seller']);

        if ($request->boolean('active_only', false)) {
            $query->usable();
        }

        $perPage = $request->integer('per_page', 15);
        $packages = $query->latest()->paginate($perPage);

        return CustomerPackageResource::collection($packages);
    }

    public function appointments(Request $request, Customer $customer): AnonymousResourceCollection
    {
        $query = $customer->appointments()->with(['service', 'staff']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->boolean('upcoming', false)) {
            $query->upcoming();
        }

        $perPage = $request->integer('per_page', 15);
        $appointments = $query->latest('appointment_date')->paginate($perPage);

        return AppointmentResource::collection($appointments);
    }
}
