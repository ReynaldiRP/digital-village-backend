<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\EventParticipants\EventParticipantStoreRequest;
use App\Http\Requests\EventParticipants\EventParticipantUpdateRequest;
use App\Http\Resources\EventParticipantResource;
use App\Http\Resources\PaginatedResource;
use App\Interfaces\EventParticipantRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class EventParticipantController extends Controller implements HasMiddleware
{
    private EventParticipantRepositoryInterface $eventParticipantRepository;

    public function __construct(EventParticipantRepositoryInterface $eventParticipantRepository)
    {
        $this->eventParticipantRepository = $eventParticipantRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['event-participant-read|event-participant-create|event-participant-update|event-participant-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['event-participant-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['event-participant-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['event-participant-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the event participant.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $eventParticipants = $this->eventParticipantRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Peserta Event Berhasil Diambil',
                EventParticipantResource::collection($eventParticipants),
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


            $events = $this->eventParticipantRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Peserta Event Berhasil Diambil',
                new PaginatedResource($events, EventParticipantResource::class),
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
     * Store a newly created event participant in storage.
     * @param EventParticipantStoreRequest $request
     * @return JsonResponse
     */
    public function store(EventParticipantStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $event = $this->eventParticipantRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Peserta Event Berhasil Ditambahkan',
                new EventParticipantResource($event),
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
     * Display the specified event participant.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $event = $this->eventParticipantRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Peserta Event tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Peserta Event Berhasil Diambil',
                new EventParticipantResource($event),
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
     * Update the specified event participant in storage.
     * @param EventParticipantUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(EventParticipantUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();

        try {
            $event = $this->eventParticipantRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Peserta Event tidak ditemukan',
                    null,
                    404
                );
            }

            $event = $this->eventParticipantRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Peserta Event Berhasil Diupdate',
                new EventParticipantResource($event),
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
     * Remove the specified event participant from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $event = $this->eventParticipantRepository->getById($id);

            if (!$event) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Peserta Event tidak ditemukan',
                    null,
                    404
                );
            }

            $this->eventParticipantRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Peserta Event Berhasil Dihapus',
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
