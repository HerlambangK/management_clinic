<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\TreatmentRecordResource;
use App\Models\TreatmentRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class TreatmentRecordController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = TreatmentRecord::query()->with(['appointment.service', 'customer', 'staff']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->boolean('with_photos', false)) {
            $query->withPhotos();
        }

        $perPage = $request->integer('per_page', 15);
        $treatments = $query->latest()->paginate($perPage);

        return TreatmentRecordResource::collection($treatments);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id', 'unique:treatment_records,appointment_id'],
            'customer_id' => ['required', 'exists:customers,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'products_used' => ['nullable', 'array'],
            'products_used.*' => ['string', 'max:255'],
            'before_photo' => ['nullable', 'image', 'max:5120'],
            'after_photo' => ['nullable', 'image', 'max:5120'],
            'recommendations' => ['nullable', 'string', 'max:1000'],
            'follow_up_date' => ['nullable', 'date', 'after:today'],
        ]);

        $validated['staff_id'] = auth()->id();

        if ($request->hasFile('before_photo')) {
            $validated['before_photo'] = $request->file('before_photo')
                ->store('treatments/before', 'public');
        }

        if ($request->hasFile('after_photo')) {
            $validated['after_photo'] = $request->file('after_photo')
                ->store('treatments/after', 'public');
        }

        $treatment = TreatmentRecord::create($validated);
        $treatment->load(['appointment.service', 'customer', 'staff']);

        return response()->json([
            'message' => 'Treatment record berhasil dibuat',
            'data' => new TreatmentRecordResource($treatment),
        ], 201);
    }

    public function show(TreatmentRecord $treatment): JsonResponse
    {
        $treatment->load(['appointment.service', 'customer', 'staff']);

        return response()->json([
            'data' => new TreatmentRecordResource($treatment),
        ]);
    }

    public function update(Request $request, TreatmentRecord $treatment): JsonResponse
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string', 'max:2000'],
            'products_used' => ['nullable', 'array'],
            'products_used.*' => ['string', 'max:255'],
            'before_photo' => ['nullable', 'image', 'max:5120'],
            'after_photo' => ['nullable', 'image', 'max:5120'],
            'recommendations' => ['nullable', 'string', 'max:1000'],
            'follow_up_date' => ['nullable', 'date'],
        ]);

        if ($request->hasFile('before_photo')) {
            if ($treatment->before_photo) {
                Storage::disk('public')->delete($treatment->before_photo);
            }
            $validated['before_photo'] = $request->file('before_photo')
                ->store('treatments/before', 'public');
        }

        if ($request->hasFile('after_photo')) {
            if ($treatment->after_photo) {
                Storage::disk('public')->delete($treatment->after_photo);
            }
            $validated['after_photo'] = $request->file('after_photo')
                ->store('treatments/after', 'public');
        }

        $treatment->update($validated);
        $treatment->load(['appointment.service', 'customer', 'staff']);

        return response()->json([
            'message' => 'Treatment record berhasil diperbarui',
            'data' => new TreatmentRecordResource($treatment),
        ]);
    }

    public function destroy(TreatmentRecord $treatment): JsonResponse
    {
        if ($treatment->before_photo) {
            Storage::disk('public')->delete($treatment->before_photo);
        }

        if ($treatment->after_photo) {
            Storage::disk('public')->delete($treatment->after_photo);
        }

        $treatment->delete();

        return response()->json([
            'message' => 'Treatment record berhasil dihapus',
        ]);
    }
}
