<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Interfaces\DashboardRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private DashboardRepositoryInterface $dashboardRepository;

    public function __construct(DashboardRepositoryInterface $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData()
    {
        try {
            $data = $this->dashboardRepository->getDashboardData();
            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data dashboard',
                $data,
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

    public function getRecentSocialAssistances(Request $request)
    {
        try {
            $limit = $request->query('limit', 4);
            $data = $this->dashboardRepository->getRecentSocialAssistances($limit);
            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data bantuan sosial terbaru',
                $data,
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

    public function getRecentDevelopmentApplicants(Request $request)
    {
        try {
            $limit = $request->query('limit', 4);
            $data = $this->dashboardRepository->getRecentDevelopmentApplicants($limit);
            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data pengajuan pembangunan terbaru',
                $data,
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

    public function getAgeDistribution(Request $request)
    {
        try {
            $limit = $request->query('limit', 4);
            $data = $this->dashboardRepository->getAgeDistribution($limit);
            return ResponseHelper::jsonResponse(
                true,
                'Berhasil mengambil data distribusi usia',
                $data,
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
