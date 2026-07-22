<?php

use App\Http\Controllers\Api\AddressBookController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Authentication Routes
Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);

// Protected Routes (Sanctum Token Authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Authenticated User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    // Address Book RESTful CRUD Routes (Protected + Controller -> Service -> Repository pattern)
    Route::apiResource('/v1/address-book', AddressBookController::class);
});
