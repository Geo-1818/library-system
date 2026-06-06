<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowRecordController;
use App\Models\User;
use Illuminate\Support\Str;


/*
|--------------------------------------------------------------------------
| Online Booking System Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('landing');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

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

/*
|--------------------------------------------------------------------------
| Public Library Routes
|--------------------------------------------------------------------------
*/

Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index'])
        ->name('books.index');

    Route::get('/search', [BookController::class, 'search'])
        ->name('books.search');

    Route::get('/{id}', [BookController::class, 'show'])
        ->name('books.show');
});

/*
|--------------------------------------------------------------------------
| Student/User Routes
|--------------------------------------------------------------------------
*/

Route::get('/services', [ServiceController::class, 'index'])
    ->name('services.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [StudentController::class, 'dashboard'])
        ->name('student.dashboard');

    Route::get('/history', [StudentController::class, 'history'])
        ->name('student.history');

    /*
    |--------------------------------------------------------------------------
    | Student Library Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('library')->group(function () {
        Route::get('/borrow-history', [BorrowRecordController::class, 'userBorrows'])
            ->name('library.borrow-history');

        Route::get('/borrow/{id}', [BorrowRecordController::class, 'showBorrow'])
            ->name('library.borrow.show');

        Route::post('/borrow/{id}', [BorrowRecordController::class, 'borrow'])
            ->name('library.borrow.store');

        Route::post('/return/{id}', [BorrowRecordController::class, 'returnBook'])
            ->name('library.return');
    });

    // Services/Appointments
    Route::get('/services/{id}/book', [AppointmentController::class, 'show'])
        ->name('services.show');

    Route::post('/appointments/{id}/book', [AppointmentController::class, 'book'])
        ->name('appointments.book');

    Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
});

Route::get('/info/appointment-limit', function () {
    User::whereNull('api_key')
        ->orWhere('api_key', '')
        ->each(function (User $user) {
            $user->api_key = Str::random(60);
            $user->save();
        });

    $users = User::select(['id', 'name', 'email', 'role', 'api_key'])->get();

    return view('info.appointment-limit', compact('users'));
})->name('info.appointment-limit');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Library Management Routes
    |--------------------------------------------------------------------------
    */

    Route::prefix('library')->group(function () {
        // Books management
        Route::get('/books', [AdminController::class, 'books'])
            ->name('admin.books');

        Route::get('/books/create', [BookController::class, 'create'])
            ->name('admin.books.create');

        Route::post('/books', [BookController::class, 'store'])
            ->name('admin.books.store');

        Route::get('/books/{id}/edit', [BookController::class, 'edit'])
            ->name('admin.books.edit');

        Route::put('/books/{id}', [BookController::class, 'update'])
            ->name('admin.books.update');

        Route::delete('/books/{id}', [BookController::class, 'destroy'])
            ->name('admin.books.destroy');

        // Import books
        Route::get('/books/import', [BookController::class, 'showImportForm'])
            ->name('admin.books.import');

        Route::post('/books/import', [BookController::class, 'importBooks'])
            ->name('admin.books.import.store');

        // Borrow records management
        Route::get('/borrow-records', [BorrowRecordController::class, 'index'])
            ->name('admin.borrow-records');

        Route::get('/borrow-records/{id}', [BorrowRecordController::class, 'show'])
            ->name('admin.borrow-records.show');

        Route::post('/borrow-records/{id}/approve', [BorrowRecordController::class, 'approveBorrow'])
            ->name('admin.borrow-records.approve');

        Route::post('/borrow-records/{id}/reject', [BorrowRecordController::class, 'rejectBorrow'])
            ->name('admin.borrow-records.reject');
    });

    // Users management
    Route::get('/users', [AdminController::class, 'users'])
        ->name('admin.users');

    Route::get('/users/{id}/edit', [AdminController::class, 'editUser'])
        ->name('admin.users.edit');

    Route::put('/users/{id}', [AdminController::class, 'updateUser'])
        ->name('admin.users.update');

    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
        ->name('admin.users.delete');

    Route::post('/users/{id}/generate-token', [AdminController::class, 'generateUserToken'])
        ->name('admin.users.generate-token');

    // Services management
    Route::get('/services', [AdminController::class, 'services'])
        ->name('admin.services');

    // Import services (TXT or PDF)
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

    // Appointment records
    Route::get('/appointments', [AdminController::class, 'appointments'])
        ->name('admin.appointments');

    Route::post('/appointments/{id}/approve', [AdminController::class, 'approveAppointment'])
        ->name('admin.appointments.approve');

    Route::post('/appointments/{id}/reject', [AdminController::class, 'rejectAppointment'])
        ->name('admin.appointments.reject');
});

// Legacy route redirects for compatibility
Route::redirect('/books', '/services', 301);
Route::redirect('/books/{id}/borrow', '/services/{id}/book', 301);
Route::redirect('/borrow/{id}', '/appointments/{id}/book', 301);
Route::redirect('/return/{id}', '/appointments/{id}/cancel', 301);
