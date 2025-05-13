<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Http\Resources\EventResource;
use Illuminate\Http\JsonResponse;
use App\Traits\ApiResponseTrait;
use App\Services\EventService;

class EventController extends Controller
{
    use ApiResponseTrait;
    protected EventService $eventService;
    protected string $resourceName = 'Event';
    
    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function index(): JsonResponse
    {
        $events = $this->eventService->getAll();
        return $this->paginatedResponse(EventResource::collection($events), "{$this->resourceName}s retrieved successfully.");
    }

    public function store(EventRequest $request): JsonResponse
    {
        $event = $this->eventService->create($request->validated());
        return $this->successResponse(new EventResource($event), "{$this->resourceName} created successfully.", 201);
    }

    public function update(EventRequest $request, int $id)
    {

        try {
            $event = $this->eventService->find($id);
            $updated = $this->eventService->update($event, $request->validated());
            return $this->successResponse(new EventResource($updated), "{$this->resourceName} updated successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return  $this->errorResponse('Event not found.', [], 404);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $event = $this->eventService->find($id);
            return $this->successResponse(new EventResource($event), "{$this->resourceName} retrieved successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return  $this->errorResponse('Event not found.', [], 404);
        }
    }

    public function destroy(int $id)
    {
       
        try {
            $event = $this->eventService->find($id);
            $this->eventService->delete($event);
            return $this->successResponse(null, "{$this->resourceName} deleted successfully.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return  $this->errorResponse("{$this->resourceName} not found.", [], 404);
        }
    }
}
