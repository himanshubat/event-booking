<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use Illuminate\Http\JsonResponse;
use App\Models\Event;

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        $events = Event::paginate(10);
        return response()->json([
            'success' => true,
            'message' => 'Event retrieved successfully.',
            'data' => EventResource::collection($events),
            'meta' => [
                'current_page' => $events->currentPage(),
                'last_page' => $events->lastPage(),
                'per_page' => $events->perPage(),
                'total' => $events->total(),
            ]
        ]);
    }

    public function store(EventRequest $request): JsonResponse
    {
        $event = Event::create($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Event created successfully.',
            'data' => new EventResource($event),
        ], 201);
    }

    public function update(EventRequest $request, Event $event): JsonResponse
    {
        $event->update($request->validated());
        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully.',
            'data' => new EventResource($event),
        ], 200);
    }

    public function show(Event $event): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Event detail retrieved successfully.',
            'data' => new EventResource($event),
        ], 200);
    }

    public function destroy(Event $event): JsonResponse
    {
        $event->delete();
        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully.',
            'data'   => NULL
        ], 200);
    }
}
