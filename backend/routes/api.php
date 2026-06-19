<?php

use App\Http\Controllers\Api\PublicLeadController;
use App\Http\Controllers\Api\PublicContentController;
use App\Http\Controllers\Api\PortalLookupController;
use App\Http\Controllers\Api\CompanyPortalLookupController;
use Illuminate\Support\Facades\Route;

Route::post('/leads', PublicLeadController::class);
Route::get('/content/home', [PublicContentController::class, 'home']);
Route::post('/portal/lookup', PortalLookupController::class);
Route::post('/company-portal/lookup', CompanyPortalLookupController::class);
