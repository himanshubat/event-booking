<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\AttendeeResource;
use App\Http\Requests\AttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;

class AttendeeController extends Controller
{
    public function index(): JsonResponse
    {
        $attendees = Attendee::paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Attendee retrieved successfully.',
            'data' => AttendeeResource::collection($attendees),
            'meta' => [
                'current_page' => $attendees->currentPage(),
                'last_page' => $attendees->lastPage(),
                'per_page' => $attendees->perPage(),
                'total' => $attendees->total(),
            ]
        ]);
    }

    public function store(AttendeeRequest $request): JsonResponse
    {
        $attendee = Attendee::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Attendee created successfully.',
            'data' => new AttendeeResource($attendee),
        ], 201);
    }

    public function show(Attendee $attendee): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Attendee retrieved successfully.',
            'data' => new AttendeeResource($attendee),
        ], 200);
    }

    public function update(UpdateAttendeeRequest $request, Attendee $attendee): JsonResponse
    {
        $attendee->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Attendee updated successfully.',
            'data' => new AttendeeResource($attendee),
        ], 200);
    }

    public function destroy(Attendee $attendee): JsonResponse
    {
        $attendee->delete();
        return response()->json([
            'success' => true,
            'message' => 'Attendee deleted successfully.',
            'data' => NULL,
        ], 200);
    }
}
