<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\HeadOfFamilies\HeadOfFamilyStoreRequest;
use App\Http\Requests\HeadOfFamilies\HeadOfFamilyUpdateRequest;
use App\Http\Resources\HeadOfFamilyResource;
use App\Http\Resources\PaginatedResource;
use App\Interfaces\HeadOfFamilyRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Middleware\PermissionMiddleware;

class HeadOfFamilyController extends Controller implements HasMiddleware
{
    private HeadOfFamilyRepositoryInterface $headOfFamilyRepository;

    public function __construct(HeadOfFamilyRepositoryInterface $headOfFamilyRepository)
    {
        $this->headOfFamilyRepository = $headOfFamilyRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['head-of-family-read|head-of-family-create|head-of-family-update|head-of-family-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['head-of-family-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['head-of-family-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['head-of-family-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the Head Of Families.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Diambil',
                HeadOfFamilyResource::collection($headOfFamilies),
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
     * Get all head of families paginated.
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


            $headOfFamilies = $this->headOfFamilyRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Diambil',
                new PaginatedResource($headOfFamilies, HeadOfFamilyResource::class),
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
     * Store a newly created head of families in storage.
     * @param HeadOfFamilyStoreRequest $request
     * @return JsonResponse
     */
    public function store(HeadOfFamilyStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $headOfFamilies = $this->headOfFamilyRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Ditambahkan',
                new HeadOfFamilyResource($headOfFamilies),
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
     * Display the specified head of family.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $headOfFamilies = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamilies) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Kepala Keluarga Tidak Ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Detail Kepala Keluarga Berhasil Diambil',
                new HeadOfFamilyResource($headOfFamilies),
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
     * Update the specified head of family in storage.
     * @param HeadOfFamilyUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HeadOfFamilyUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();

        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Kepala Keluarga Tidak Ditemukan',
                    null,
                    404
                );
            }

            $headOfFamily = $this->headOfFamilyRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Diupdate',
                new HeadOfFamilyResource($headOfFamily),
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
     * Remove the specified head of family from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $headOfFamily = $this->headOfFamilyRepository->getById($id);

            if (!$headOfFamily) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Kepala Keluarga Tidak Ditemukan',
                    null,
                    404
                );
            }

            $headOfFamily = $this->headOfFamilyRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Kepala Keluarga Berhasil Dihapus',
                new HeadOfFamilyResource($headOfFamily),
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
