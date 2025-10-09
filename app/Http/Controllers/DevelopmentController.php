<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Developments\DevelopmentStoreRequest;
use App\Http\Requests\Developments\DevelopmentUpdateRequest;
use App\Http\Resources\DevelopmentResource;
use App\Http\Resources\PaginatedResource;
use App\Interfaces\DevelopmentRepositoryInterface;
use App\Models\Development;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class DevelopmentController extends Controller implements HasMiddleware
{
    private DevelopmentRepositoryInterface $developmentRepository;

    public function __construct(DevelopmentRepositoryInterface $developmentRepository)
    {
        $this->developmentRepository = $developmentRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['development-read|development-create|development-update|development-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['development-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['development-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['development-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the development.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $development = $this->developmentRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data pembangunan',
                DevelopmentResource::collection($development),
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
     * Get all developments paginated.
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


            $developments = $this->developmentRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data pembangunan Berhasil Diambil',
                new PaginatedResource($developments, DevelopmentResource::class),
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
     * Store a newly created development in storage.
     * @param DevelopmentStoreRequest $request
     * @return JsonResponse
     */
    public function store(DevelopmentStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $development = $this->developmentRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan data pembangunan',
                new DevelopmentResource($development),
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
     * Display the specified development.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $development = $this->developmentRepository->getById($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data pembangunan',
                new DevelopmentResource($development),
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
     * Update the specified development in storage.
     * @param DevelopmentUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(DevelopmentUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data pembangunan tidak ditemukan',
                    null,
                    404
                );
            }

            $development = $this->developmentRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui data pembangunan',
                new DevelopmentResource($development),
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
     * Remove the specified development from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $development = $this->developmentRepository->getById($id);

            if (!$development) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data pembangunan tidak ditemukan',
                    null,
                    404
                );
            }

            $this->developmentRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus data pembangunan',
                null,
                204
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
