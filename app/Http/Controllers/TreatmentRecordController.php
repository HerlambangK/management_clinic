<?php

namespace App\Http\Controllers;

use App\Http\Requests\TreatmentRecordRequest;
use App\Models\Appointment;
use App\Models\TreatmentRecord;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class TreatmentRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = TreatmentRecord::with(['customer', 'appointment.service', 'staff'])
            ->latest();

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $records = $query->paginate(15)->withQueryString();

        return view('treatment-records.index', compact('records'));
    }

    public function create(Request $request): View
    {
        $appointmentId = $request->get('appointment_id');
        $appointment = null;

        if ($appointmentId) {
            $appointment = Appointment::with(['customer', 'service', 'staff', 'transaction.items'])
                ->where('status', 'completed')
                ->whereDoesntHave('treatmentRecord')
                ->findOrFail($appointmentId);
        }

        $completedAppointments = Appointment::with(['customer', 'service', 'transaction.items'])
            ->where('status', 'completed')
            ->whereDoesntHave('treatmentRecord')
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('treatment-records.create', compact('appointment', 'completedAppointments'));
    }

    public function store(TreatmentRecordRequest $request): RedirectResponse
    {
        $appointment = Appointment::with('customer')->findOrFail($request->appointment_id);

        $data = [
            'appointment_id' => $appointment->id,
            'customer_id' => $appointment->customer_id,
            'staff_id' => auth()->id(),
            'notes' => $request->notes,
            'recommendations' => $request->recommendations,
            'follow_up_date' => $request->follow_up_date,
        ];

        // Handle multiple before photos
        if ($request->hasFile('before_photos')) {
            $beforePhotos = [];
            foreach ($request->file('before_photos') as $photo) {
                $beforePhotos[] = $photo->store('treatment-photos', 'public');
            }
            $data['before_photos'] = $beforePhotos;
        }

        // Handle multiple after photos
        if ($request->hasFile('after_photos')) {
            $afterPhotos = [];
            foreach ($request->file('after_photos') as $photo) {
                $afterPhotos[] = $photo->store('treatment-photos', 'public');
            }
            $data['after_photos'] = $afterPhotos;
        }

        $record = TreatmentRecord::create($data);

        return redirect()->route('treatment-records.show', $record)
            ->with('success', __('treatment.created'));
    }

    public function show(TreatmentRecord $treatmentRecord): View
    {
        $treatmentRecord->load(['customer', 'appointment.service', 'staff']);

        return view('treatment-records.show', compact('treatmentRecord'));
    }

    public function edit(TreatmentRecord $treatmentRecord): View
    {
        $treatmentRecord->load(['customer', 'appointment.service', 'appointment.transaction.items', 'staff']);

        return view('treatment-records.edit', compact('treatmentRecord'));
    }

    public function update(TreatmentRecordRequest $request, TreatmentRecord $treatmentRecord): RedirectResponse
    {
        $data = [
            'notes' => $request->notes,
            'recommendations' => $request->recommendations,
            'follow_up_date' => $request->follow_up_date,
        ];

        // Handle before photos - keep selected existing + add new
        $keepBeforePhotos = $request->input('keep_before_photos', []);
        $currentBeforePhotos = $treatmentRecord->before_photos ?? [];

        // Delete photos that are not in keep list
        foreach ($currentBeforePhotos as $photo) {
            if (! in_array($photo, $keepBeforePhotos)) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Start with kept photos
        $beforePhotos = $keepBeforePhotos;

        // Add new uploaded photos
        if ($request->hasFile('before_photos')) {
            foreach ($request->file('before_photos') as $photo) {
                $beforePhotos[] = $photo->store('treatment-photos', 'public');
            }
        }

        $data['before_photos'] = ! empty($beforePhotos) ? array_values($beforePhotos) : null;

        // Handle after photos - keep selected existing + add new
        $keepAfterPhotos = $request->input('keep_after_photos', []);
        $currentAfterPhotos = $treatmentRecord->after_photos ?? [];

        // Delete photos that are not in keep list
        foreach ($currentAfterPhotos as $photo) {
            if (! in_array($photo, $keepAfterPhotos)) {
                Storage::disk('public')->delete($photo);
            }
        }

        // Start with kept photos
        $afterPhotos = $keepAfterPhotos;

        // Add new uploaded photos
        if ($request->hasFile('after_photos')) {
            foreach ($request->file('after_photos') as $photo) {
                $afterPhotos[] = $photo->store('treatment-photos', 'public');
            }
        }

        $data['after_photos'] = ! empty($afterPhotos) ? array_values($afterPhotos) : null;

        $treatmentRecord->update($data);

        return redirect()->route('treatment-records.show', $treatmentRecord)
            ->with('success', __('treatment.updated'));
    }

    public function destroy(TreatmentRecord $treatmentRecord): RedirectResponse
    {
        // Delete all before photos
        if (! empty($treatmentRecord->before_photos)) {
            foreach ($treatmentRecord->before_photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }
        // Delete all after photos
        if (! empty($treatmentRecord->after_photos)) {
            foreach ($treatmentRecord->after_photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $customerId = $treatmentRecord->customer_id;
        $treatmentRecord->delete();

        return redirect()->route('customers.show', $customerId)
            ->with('success', __('treatment.deleted'));
    }

    public function deletePhoto(Request $request, TreatmentRecord $treatmentRecord): RedirectResponse
    {
        $type = $request->get('type');
        $index = $request->get('index', 0);

        if ($type === 'before' && ! empty($treatmentRecord->before_photos)) {
            $photos = $treatmentRecord->before_photos;
            if (isset($photos[$index])) {
                Storage::disk('public')->delete($photos[$index]);
                unset($photos[$index]);
                $treatmentRecord->update(['before_photos' => array_values($photos) ?: null]);
            }
        } elseif ($type === 'after' && ! empty($treatmentRecord->after_photos)) {
            $photos = $treatmentRecord->after_photos;
            if (isset($photos[$index])) {
                Storage::disk('public')->delete($photos[$index]);
                unset($photos[$index]);
                $treatmentRecord->update(['after_photos' => array_values($photos) ?: null]);
            }
        }

        return back()->with('success', __('treatment.photo_deleted'));
    }

    public function exportPdf(TreatmentRecord $treatmentRecord): Response
    {
        $treatmentRecord->load(['customer', 'appointment.service', 'staff']);

        $pdf = Pdf::loadView('treatment-records.pdf', [
            'record' => $treatmentRecord,
        ]);

        $filename = 'treatment-record-'.$treatmentRecord->id.'-'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }

    public function exportCustomerPdf(Request $request): Response
    {
        $customerId = $request->get('customer_id');

        $records = TreatmentRecord::with(['customer', 'appointment.service', 'staff'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($records->isEmpty()) {
            abort(404, __('treatment.no_records'));
        }

        $customer = $records->first()->customer;

        $pdf = Pdf::loadView('treatment-records.pdf-customer', [
            'records' => $records,
            'customer' => $customer,
        ]);

        $filename = 'treatment-history-'.$customer->id.'-'.now()->format('Ymd').'.pdf';

        return $pdf->download($filename);
    }
}
