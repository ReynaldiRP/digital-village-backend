<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\Profiles\ProfileStoreRequest;
use App\Http\Resources\ProfileResource;
use App\Interfaces\ProfileRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VillageProfileController extends Controller
{
    private ProfileRepositoryInterface $villageProfileRepository;

    public function __construct(ProfileRepositoryInterface $villageProfileRepository)
    {
        $this->villageProfileRepository = $villageProfileRepository;
    }

    /**
     * Display a listing of the village profiles.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $villageProfile = $this->villageProfileRepository->get();

            if (!$villageProfile) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data profil desa tidak ada',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data profil desa berhasil diambil',
                new ProfileResource($villageProfile),
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
     * Store a newly created village profile in storage.
     * @param ProfileStoreRequest $request
     * @return JsonResponse
     */
    public function store(ProfileStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $villageProfile = $this->villageProfileRepository->create($request);
            
            return ResponseHelper::jsonResponse(
                true,
                'Data profil desa berhasil disimpan',
                new ProfileResource($villageProfile),
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
     * Update the specified village profile in storage.
     * @param ProfileStoreRequest $request
     * @return JsonResponse
     */
    public function update(ProfileStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $villageProfile = $this->villageProfileRepository->update($request);
            return ResponseHelper::jsonResponse(
                true,
                'Data profil desa berhasil diperbarui',
                new ProfileResource($villageProfile),
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
