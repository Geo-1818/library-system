<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\BorrowRecordController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AppointmentController as AppointmentApiController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::get('/search', [BookController::class, 'search']);
});


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
