<?php

use App\Http\Controllers\Api\PublicLeadController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\PublicCourseController;
use App\Http\Controllers\Api\PortalAuthController;
use App\Http\Controllers\Api\PortalLessonController;
use App\Http\Controllers\Api\PortalLookupController;
use App\Http\Controllers\Api\CompanyPortalLookupController;
use App\Http\Controllers\Api\AffiliatePortalLookupController;
use App\Http\Controllers\Api\BiReportExportController;
use Illuminate\Support\Facades\Route;

Route::post('/leads', PublicLeadController::class);
Route::get('/content/home', [PublicContentController::class, 'home']);
Route::get('/courses', [PublicCourseController::class, 'index']);
Route::get('/courses/{slug}', [PublicCourseController::class, 'show']);
Route::post('/portal/auth/request', [PortalAuthController::class, 'requestCode']);
Route::post('/portal/auth/verify', [PortalAuthController::class, 'verifyCode']);
Route::post('/portal/lookup', PortalLookupController::class);
Route::post('/portal/lessons/{slug}', [PortalLessonController::class, 'show']);
Route::post('/portal/lessons/{slug}/progress', [PortalLessonController::class, 'updateProgress']);
Route::post('/company-portal/lookup', CompanyPortalLookupController::class);
Route::post('/affiliate-portal/lookup', AffiliatePortalLookupController::class);
Route::get('/reports/{report}.csv', BiReportExportController::class);
