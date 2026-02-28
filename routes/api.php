<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Api Working',
    ]);
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});

Route::middleware('auth:sanctum')->group(function(){

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request){
        $request->fulfill();

        return response()->json([
            'message' => 'Email verified successfully.'
        ]);

    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request){
        $request->user()->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Verification link sent!'
        ]);
    })->middleware('throttle:6,1')->name('verification.send');
});