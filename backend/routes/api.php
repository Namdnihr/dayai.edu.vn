<?php

use App\Http\Controllers\Api\PublicLeadController;
use App\Http\Controllers\Api\PublicAccountController;
use App\Http\Controllers\Api\PublicCourseAccessController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\PublicCourseController;
use App\Http\Controllers\Api\PortalAuthController;
use App\Http\Controllers\Api\PortalLessonController;
use App\Http\Controllers\Api\PortalLookupController;
use App\Http\Controllers\Api\PortalQuizController;
use App\Http\Controllers\Api\CompanyPortalLookupController;
use App\Http\Controllers\Api\AffiliatePortalLookupController;
use App\Http\Controllers\Api\BiReportExportController;
use App\Http\Controllers\Api\HealthCheckController;
use Illuminate\Support\Facades\Route;

Route::post('/leads', PublicLeadController::class);
Route::post('/course-auth/register', [PublicAccountController::class, 'register']);
Route::post('/course-auth/verify-email', [PublicAccountController::class, 'verifyEmail']);
Route::post('/course-auth/login', [PublicAccountController::class, 'login']);
Route::get('/content/home', [PublicContentController::class, 'home']);
Route::get('/courses', [PublicCourseController::class, 'index']);
Route::get('/courses/{slug}', [PublicCourseController::class, 'show']);
Route::post('/course-access/free-enroll', [PublicCourseAccessController::class, 'freeEnroll']);
Route::post('/course-access/checkout', [PublicCourseAccessController::class, 'checkout']);
Route::post('/course-access/checkout/{orderCode}/mark-paid', [PublicCourseAccessController::class, 'markPaid']);
Route::post('/portal/auth/request', [PortalAuthController::class, 'requestCode']);
Route::post('/portal/auth/verify', [PortalAuthController::class, 'verifyCode']);
Route::post('/portal/lookup', PortalLookupController::class);
Route::post('/portal/lessons/{slug}', [PortalLessonController::class, 'show']);
Route::post('/portal/lessons/{slug}/progress', [PortalLessonController::class, 'updateProgress']);
Route::post('/portal/assessments/{assessment}/start', [PortalQuizController::class, 'start']);
Route::post('/portal/quiz-attempts/{attemptCode}/submit', [PortalQuizController::class, 'submit']);
Route::post('/company-portal/lookup', CompanyPortalLookupController::class);
Route::post('/affiliate-portal/lookup', AffiliatePortalLookupController::class);
Route::get('/reports/{report}.csv', BiReportExportController::class);
Route::get('/health', HealthCheckController::class);
