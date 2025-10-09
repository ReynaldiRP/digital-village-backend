<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\FamilyMembers\FamilyMemberStoreRequest;
use App\Http\Requests\FamilyMembers\FamilyMemberUpdateRequest;
use App\Http\Resources\FamilyMemberResource;
use App\Http\Resources\PaginatedResource;
use App\Interfaces\FamilyMemberRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class FamilyMemberController extends Controller implements HasMiddleware
{
    private FamilyMemberRepositoryInterface $familyMemberRepository;

    public function __construct(FamilyMemberRepositoryInterface $familyMemberRepository)
    {
        $this->familyMemberRepository = $familyMemberRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['family-member-read|family-member-create|family-member-update|family-member-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['family-member-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['family-member-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['family-member-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the family members.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $familyMembers = $this->familyMemberRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data anggota keluarga',
                FamilyMemberResource::collection($familyMembers),
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
     * Get all family members paginated.
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

            $familyMembers = $this->familyMemberRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data anggota keluarga',
                PaginatedResource::make($familyMembers, FamilyMemberResource::class),
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
     * Store a newly created family member in storage.
     * @param FamilyMemberStoreRequest $request
     * @return JsonResponse
     */
    public function store(FamilyMemberStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menambahkan anggota keluarga',
                new FamilyMemberResource($familyMember),
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
     * Display the specified family member.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data anggota keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mendapatkan data anggota keluarga',
                new FamilyMemberResource($familyMember),
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
     * Update the specified family member in storage.
     * @param FamilyMemberUpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(FamilyMemberUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();

        try {
            $familyMember = $this->familyMemberRepository->getById($id);

            if (!$familyMember) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data anggota keluarga tidak ditemukan',
                    null,
                    404
                );
            }

            $familyMember = $this->familyMemberRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil memperbarui anggota keluarga',
                new FamilyMemberResource($familyMember),
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
     * Remove the specified family member from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $familyMember = $this->familyMemberRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Berhasil menghapus anggota keluarga',
                new FamilyMemberResource($familyMember),
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
