<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceCategoryResource;
use App\Models\ServiceCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ServiceCategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = ServiceCategory::query()->active()->ordered();

        if ($request->boolean('with_services', false)) {
            $query->with(['services' => fn ($q) => $q->active()]);
        }

        if ($request->boolean('with_count', false)) {
            $query->withCount(['services' => fn ($q) => $q->active()]);
        }

        return ServiceCategoryResource::collection($query->get());
    }

    public function show(ServiceCategory $serviceCategory): JsonResponse
    {
        $serviceCategory->load(['services' => fn ($q) => $q->active()]);

        return response()->json([
            'data' => new ServiceCategoryResource($serviceCategory),
        ]);
    }
}
