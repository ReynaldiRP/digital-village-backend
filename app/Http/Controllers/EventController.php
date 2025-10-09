<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Events\EventStoreRequest;
use App\Http\Requests\Events\EventUpdateRequest;
use App\Http\Resources\EventResource;
use App\Http\Resources\PaginatedResource;
use App\Interfaces\EventRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventController extends Controller implements HasMiddleware
{
    private EventRepositoryInterface $eventRepository;

    public function __construct(EventRepositoryInterface $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-read|event-create|event-update|event-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['event-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the events.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $events = $this->eventRepository->getAll(
                $request->input('search'),
                $request->input('limit'),
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Events berhasil diambil',
                EventResource::collection($events),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Get all events paginated.
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllPaginated(Request $request): JsonResponse
    {
        try {
            $request = $request->validate([
                'search' => 'nullable|string',
                'row_per_page' => 'required',
            ]);


            $events = $this->eventRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                new PaginatedResource($events, EventResource::class),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Store a newly created event in storage.
     * @param EventStoreRequest $request
     * @return JsonResponse
     */
    public function store(EventStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $event = $this->eventRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Ditambahkan',
                new EventResource($event),
                201
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Display the specified event.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Event Berhasil Diambil',
                new EventResource($event),
                200
            );
        } catch (\Exception $th) {
            return ResponseHelper::jsonResponse(
                false,
                $th->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Update the specified event in storage.
     * @param EventUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(EventUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();

        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Diupdate',
                new EventResource($event),
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Remove the specified event from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $event = $this->eventRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Event tidak ditemukan',
                    null,
                    404
                );
            }

            $this->eventRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Event Berhasil Dihapus',
                null,
                200
            );
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(
                false,
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
