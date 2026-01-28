<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DevelopmentApplicantController;
use App\Http\Controllers\DevelopmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventParticipantController;
use App\Http\Controllers\FamilyMemberController;
use App\Http\Controllers\HeadOfFamilyController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\SocialAssistanceController;
use App\Http\Controllers\SocialAssistanceRecipientController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VillageProfileController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('dashboard/get-dashboard-data', [\App\Http\Controllers\DashboardController::class, 'getDashboardData']);
    Route::get('dashboard/get-recent-social-assistances', [\App\Http\Controllers\DashboardController::class, 'getRecentSocialAssistances']);
    Route::get('dashboard/get-recent-development-applicants', [\App\Http\Controllers\DashboardController::class, 'getRecentDevelopmentApplicants']);
    Route::get('dashboard/get-age-distribution', [\App\Http\Controllers\DashboardController::class, 'getAgeDistribution']);

    Route::apiResource('users', UserController::class);
    Route::get('users/all/paginated', [UserController::class, 'getAllPaginated']);

    Route::apiResource('head-of-families', HeadOfFamilyController::class);
    Route::get('head-of-families/all/paginated', [HeadOfFamilyController::class, 'getAllPaginated']);

    Route::apiResource('family-members', FamilyMemberController::class);
    Route::get('family-members/all/paginated', [FamilyMemberController::class, 'getAllPaginated']);

    Route::apiResource('social-assistances', SocialAssistanceController::class);
    Route::get('social-assistances/all/paginated', [SocialAssistanceController::class, 'getAllPaginated']);

    Route::apiResource('social-assistance-recipients', SocialAssistanceRecipientController::class);
    Route::get('social-assistance-recipients/all/paginated', [SocialAssistanceRecipientController::class, 'getAllPaginated']);

    Route::apiResource('events', EventController::class);
    Route::get('events/all/paginated', [EventController::class, 'getAllPaginated']);

    Route::apiResource('event-participants', EventParticipantController::class);
    Route::get('event-participants/all/paginated', [EventParticipantController::class, 'getAllPaginated']);

    Route::apiResource('developments', DevelopmentController::class);
    Route::get('developments/all/paginated', [DevelopmentController::class, 'getAllPaginated']);

    Route::apiResource('development-applicants', DevelopmentApplicantController::class);
    Route::get('development-applicants/all/paginated', [DevelopmentApplicantController::class, 'getAllPaginated']);

    Route::apiResource('village-profiles', VillageProfileController::class)->only(['index', 'store', 'update']);
});


Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-reset-link', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});
