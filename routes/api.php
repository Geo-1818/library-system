<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowRecordController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AppointmentController as AppointmentApiController;
use App\Models\User;
use Laravel\Sanctum\PersonalAccessToken;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| API Key Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware(\App\Http\Middleware\ApiKeyAuth::class)->prefix('apikey')->group(function () {

    Route::get('/appointments', [AppointmentApiController::class, 'index']);
    Route::post('/appointments', [AppointmentApiController::class, 'store']);
    Route::get('/appointments/{id}', [AppointmentApiController::class, 'show']);
    Route::patch('/appointments/{id}', [AppointmentApiController::class, 'update']);
    Route::delete('/appointments/{id}', [AppointmentApiController::class, 'destroy']);

    Route::get('/users/api-keys', function () {
        return response()->json([
            'success' => true,
            'data' => \App\Models\User::select('id','name','email','api_key')->get()
        ]);
    });
});

/*
|--------------------------------------------------------------------------
| Public Book Routes
|--------------------------------------------------------------------------
*/

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::get('/search', [BookController::class, 'search']);
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('borrows')->group(function () {
        Route::get('/', [BorrowRecordController::class, 'userBorrows']);
        Route::post('/', [BorrowRecordController::class, 'borrow']);
        Route::post('/{id}/return', [BorrowRecordController::class, 'returnBook']);
        Route::get('/{id}', [BorrowRecordController::class, 'show']);
    });

    Route::prefix('appointments')->group(function () {
        Route::get('/', [AppointmentApiController::class, 'index']);
        Route::post('/', [AppointmentApiController::class, 'store']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', [AdminController::class, 'dashboard']);

    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users']);
        Route::put('/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/{id}', [AdminController::class, 'deleteUser']);
    });

    Route::prefix('books')->group(function () {
        Route::post('/', [BookController::class, 'store']);
        Route::put('/{id}', [BookController::class, 'update']);
        Route::delete('/{id}', [BookController::class, 'destroy']);
    });

    Route::prefix('borrows')->group(function () {
        Route::get('/', [BorrowRecordController::class, 'index']);
        Route::post('/{id}/approve', [BorrowRecordController::class, 'approveBorrow']);
        Route::post('/{id}/reject', [BorrowRecordController::class, 'rejectBorrow']);
    });
});

/*
|--------------------------------------------------------------------------
| Generate Token Route
|--------------------------------------------------------------------------
*/

Route::get('/generate-token', function () {

    $user = User::find(1);

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    return response()->json([
        'token' => $user->createToken('LibraryToken')->plainTextToken
    ]);
});

/*
|--------------------------------------------------------------------------
| Find Token Owner Route
|--------------------------------------------------------------------------
*/

Route::post('/find-token-owner', function (Request $request) {

    $request->validate([
        'token' => 'required'
    ]);

    $accessToken = PersonalAccessToken::findToken($request->token);

    if (!$accessToken) {
        return response()->json([
            'message' => 'Token not found'
        ], 404);
    }

    $user = $accessToken->tokenable;

    return response()->json([
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role ?? null,
    ]);
});

/*
|--------------------------------------------------------------------------
| Find Remember Token Owner Route
|--------------------------------------------------------------------------
*/

Route::post('/find-remember-token-owner', function (Request $request) {

    $request->validate([
        'token' => 'required|string'
    ]);

    $user = User::where('remember_token', $request->token)->first();

    if (!$user) {
        return response()->json([
            'message' => 'User not found'
        ], 404);
    }

    return response()->json([
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'remember_token' => $user->remember_token
    ]);
});