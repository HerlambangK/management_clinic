<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StaffController extends Controller
{
    /**
     * Get all staff members
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = User::query();

        if ($request->role) {
            $query->where('role', $request->role);
        }

        if ($request->boolean('active_only', true)) {
            $query->where('is_active', true);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        $staff = $query->orderBy('name')->paginate($request->per_page ?? 20);

        return UserResource::collection($staff);
    }

    /**
     * Get single staff member
     */
    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    /**
     * Get beauticians/therapists only (for appointment assignment)
     */
    public function beauticians(Request $request): AnonymousResourceCollection
    {
        $beauticians = User::where('role', 'beautician')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return UserResource::collection($beauticians);
    }
}
