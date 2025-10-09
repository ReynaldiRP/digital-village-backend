<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\SocialAssistanceRecipients\SocialAssistanceRecipientStoreRequest;
use App\Http\Requests\SocialAssistanceRecipients\SocialAssistanceRecipientUpdateRequest;
use App\Http\Resources\PaginatedResource;
use App\Http\Resources\SocialAssistanceRecipientResource;
use App\Interfaces\SocialAssistanceRecipientRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class SocialAssistanceRecipientController extends Controller implements HasMiddleware
{
    private SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository;

    public function __construct(SocialAssistanceRecipientRepositoryInterface $socialAssistanceRecipientRepository)
    {
        $this->socialAssistanceRecipientRepository = $socialAssistanceRecipientRepository;
    }

    public static function middleware()
    {
        return [
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-read|social-assistance-recipient-create|social-assistance-recipient-update|social-assistance-recipient-delete']), only: ['index', 'show', 'getAllPaginated']),
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-create']), only: ['store']),
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-update']), only: ['update']),
            new Middleware(PermissionMiddleware::using(['social-assistance-recipient-delete']), only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the social assistance recipient.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAll(
                $request->search,
                $request->limit,
                true
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan berhasil diambil',
                SocialAssistanceRecipientResource::collection($socialAssistanceRecipients),
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
     * Get all social assistance recipient paginated.
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


            $socialAssistanceRecipients = $this->socialAssistanceRecipientRepository->getAllPaginated(
                $request['search'] ?? null,
                $request['row_per_page']
            );

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan Berhasil Diambil',
                new PaginatedResource($socialAssistanceRecipients, SocialAssistanceRecipientResource::class),
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
     * Store a newly created social assistance recipient in storage.
     * @param SocialAssistanceRecipientStoreRequest $request
     * @return JsonResponse
     */
    public function store(SocialAssistanceRecipientStoreRequest $request): JsonResponse
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->create($request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan Berhasil Ditambahkan',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
     * Display the specified social assistance recipient.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(
                    true,
                    'Data Penerima Bantuan tidak ditemukan',
                    null,
                    404
                );
            }

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan Berhasil Diambil',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
     * Update the specified social assistance recipient in storage.
     * @param SocialAssistanceRecipientUpdateRequest $request
     * @param string $id
     */
    public function update(SocialAssistanceRecipientUpdateRequest $request, string $id): JsonResponse
    {
        $request = $request->validated();

        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Penerima Bantuan tidak ditemukan',
                    null,
                    404
                );
            }

            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->update($id, $request);

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan Berhasil Diupdate',
                new SocialAssistanceRecipientResource($socialAssistanceRecipient),
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
     * Remove the specified social assistance recipient from storage.
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $socialAssistanceRecipient = $this->socialAssistanceRecipientRepository->getById($id);

            if (!$socialAssistanceRecipient) {
                return ResponseHelper::jsonResponse(
                    false,
                    'Data Penerima Bantuan tidak ditemukan',
                    null,
                    404
                );
            }

            $this->socialAssistanceRecipientRepository->delete($id);

            return ResponseHelper::jsonResponse(
                true,
                'Data Penerima Bantuan Berhasil Dihapus',
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
