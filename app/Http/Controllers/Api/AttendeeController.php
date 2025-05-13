<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AttendeeResource;
use App\Http\Requests\AttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;
use App\Traits\ApiResponseTrait;
use App\Services\AttendeeService;

class AttendeeController extends Controller
{
    use ApiResponseTrait;

    protected AttendeeService $attendeeService;
    protected string $resourceName = 'Attendee';

    public function __construct(AttendeeService $attendeeService)
    {
        $this->attendeeService = $attendeeService;
    }

    public function index(): JsonResponse
    {
        $attendees = $this->attendeeService->getAll();
        return $this->paginatedResponse(AttendeeResource::collection($attendees), "{$this->resourceName}s retrieved successfully.");
    }

    public function store(AttendeeRequest $request): JsonResponse
    {
        $attendee = $this->attendeeService->create($request->validated());
        return $this->successResponse(new AttendeeResource($attendee), "{$this->resourceName} created successfully.", 201);
    }

    public function show(int $id): JsonResponse
    {
        try {
            $attendee = $this->attendeeService->find($id);
            return $this->successResponse(new AttendeeResource($attendee), "{$this->resourceName} retrieved successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse("{$this->resourceName} not found.", [], 404);
        }
    }

    public function update(UpdateAttendeeRequest $request, int $id): JsonResponse
    {
        try {
            $attendee = $this->attendeeService->find($id);
            $updated = $this->attendeeService->update($attendee, $request->validated());
            return $this->successResponse(new AttendeeResource($updated), "{$this->resourceName} updated successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse("{$this->resourceName} not found.", [], 404);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $attendee = $this->attendeeService->find($id);
            $this->attendeeService->delete($attendee);
            return $this->successResponse(null, "{$this->resourceName} deleted successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return  $this->errorResponse("{$this->resourceName} not found.", [], 404);
        }
    }
}
