<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\PaperController;

Route::get('/test', function () {
    return response()->json([
        'message' => 'Api Working',
    ]);
});
// authentification
Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
});
// email verifications 
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
// route for each roles only
Route::middleware(['auth:sanctum','role:admin'])->group(function () {

    Route::get('/admin', function(){
        return 'Admin Only';
    });

});

Route::middleware(['auth:sanctum','role:reviewer'])->group(function () {

    Route::get('/review/papers', function(){
        return 'Reviewer Panel';
    });

});

Route::middleware(['auth:sanctum','role:admin,editor'])->group(function () {

    Route::get('/manage/papers', function(){
        return 'Manage Papers';
    });

});


Route::middleware(['auth:sanctum','role:user'])->group(function(){

    Route::post('/papers', [PaperController::class,'store']);

    Route::get('/my-papers', [PaperController::class,'myPapers']);

    Route::delete('/papers/{id}', [PaperController::class,'destroy']);

});