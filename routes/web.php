<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;



Route::get('/', function () {
    return view('landing');
});



Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.store')
    ->middleware('guest');

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store')
    ->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');



Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    Route::get('/history', [StudentController::class, 'history'])
        ->name('student.history');

    Route::get('/services/{id}/book', [AppointmentController::class, 'show'])
        ->name('services.show');

    Route::post('/appointments/{id}/book', [AppointmentController::class, 'book'])
        ->name('appointments.book');

    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
});



Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    
    Route::get('/users', [AdminController::class, 'users'])
        ->name('admin.users');

    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
        ->name('admin.users.edit');

    Route::put('/users/{id}', [AdminController::class, 'updateUser'])
        ->name('admin.users.update');

    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
        ->name('admin.users.delete');

    
    Route::get('/services', [AdminController::class, 'services'])
        ->name('admin.services');

    
    Route::get('/services/import', [AdminController::class, 'showImportForm'])
        ->name('admin.services.import');

    Route::post('/services/import', [AdminController::class, 'importServices'])
        ->name('admin.services.import.store');

    Route::get('/services/{id}/edit', [AdminController::class, 'editService'])
        ->name('admin.services.edit');

    Route::put('/services/{id}', [AdminController::class, 'updateService'])
        ->name('admin.services.update');

    Route::delete('/services/{id}', [AdminController::class, 'deleteService'])
        ->name('admin.services.delete');

    
    Route::get('/appointments', [AdminController::class, 'appointments'])
        ->name('admin.appointments');

    Route::post('/appointments/{id}/approve', [AdminController::class, 'approveAppointment'])
        ->name('admin.appointments.approve');

    Route::post('/appointments/{id}/reject', [AdminController::class, 'rejectAppointment'])
        ->name('admin.appointments.reject');
});


Route::redirect('/books', '/services', 301);
Route::redirect('/books/{id}/borrow', '/services/{id}/book', 301);
Route::redirect('/borrow/{id}', '/appointments/{id}/book', 301);
Route::redirect('/return/{id}', '/appointments/{id}/cancel', 301);
