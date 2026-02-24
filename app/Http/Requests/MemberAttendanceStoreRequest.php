<?php

namespace App\Http\Requests;

use App\Services\AttendanceService;
use Illuminate\Foundation\Http\FormRequest;

class MemberAttendanceStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('customer')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'attendance_token' => ['nullable', 'string', 'max:100'],
            'scan_mode' => ['nullable', 'string', 'in:qr,location'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'accuracy' => ['nullable', 'integer', 'min:0', 'max:10000'],
            'location_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'latitude.required' => __('attendance.location_required'),
            'longitude.required' => __('attendance.location_required'),
            'latitude.between' => __('attendance.location_invalid'),
            'longitude.between' => __('attendance.location_invalid'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $token = $this->input('attendance_token');
            $store = current_store();

            if (! $store) {
                $validator->errors()->add('attendance_token', __('attendance.store_missing'));

                return;
            }

            $attendanceService = app(AttendanceService::class);

            if ($this->input('scan_mode') === 'qr' && ! $token) {
                $validator->errors()->add('attendance_token', __('attendance.qr_required'));

                return;
            }

            if ($token) {
                $randomToken = $attendanceService->extractToken($token);

                if (! $randomToken || $randomToken !== $store->attendance_token) {
                    $validator->errors()->add('attendance_token', __('attendance.qr_invalid'));
                }
            }

            if ($store->latitude === null || $store->longitude === null) {
                $validator->errors()->add('latitude', __('attendance.location_not_configured'));

                return;
            }

            $latitude = $this->input('latitude');
            $longitude = $this->input('longitude');

            if ($latitude === null || $longitude === null) {
                return;
            }

            if (! $attendanceService->isWithinRadius($store, (float) $latitude, (float) $longitude)) {
                $validator->errors()->add('latitude', __('attendance.location_out_of_range', [
                    'radius' => AttendanceService::DEFAULT_RADIUS_METERS,
                ]));
            }
        });
    }
}
