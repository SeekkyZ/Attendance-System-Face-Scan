<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\FaceRecognitionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('attendance.index');
    }
    return view('welcome');
});



// Authentication Routes - Use default Laravel auth
// Auth::routes(); // ปิดระบบ login/register

// Use default Laravel register view
// Route override removed to use Laravel default

Route::middleware('auth')->group(function () {
    // Attendance Routes
    Route::prefix('attendance')->name('attendance.')->group(function () {
        Route::get('/', [AttendanceController::class, 'index'])->name('index');
        Route::get('/history', [AttendanceController::class, 'history'])->name('history');
        Route::get('/today', [AttendanceController::class, 'today'])->name('today');
    });
    
    // Face Recognition Routes
    Route::prefix('face')->name('face.')->group(function () {
        Route::get('/register', [FaceRecognitionController::class, 'registerIndex'])->name('register');
        Route::post('/register', [FaceRecognitionController::class, 'registerFace'])->name('register.store');
        Route::get('/scan', [FaceRecognitionController::class, 'scanIndex'])->name('scan');
        Route::post('/scan', [FaceRecognitionController::class, 'scanFace'])->name('scan.process');
        Route::get('/locations', [FaceRecognitionController::class, 'getLocations'])->name('locations');
        Route::delete('/{face}', [FaceRecognitionController::class, 'deleteFace'])->name('delete');
    });
    
    // User Locations Route (outside group to avoid conflicts)
    Route::get('/locations/my-locations', [LocationController::class, 'userLocations'])->name('user-locations');
    
    // Location Routes
    Route::prefix('locations')->name('locations.')->group(function () {
        Route::get('/', [LocationController::class, 'index'])->name('index');
        Route::get('/create', [LocationController::class, 'create'])->name('create');
        Route::post('/', [LocationController::class, 'store'])->name('store');
        Route::get('/{location}', [LocationController::class, 'show'])->name('show');
        Route::get('/{location}/edit', [LocationController::class, 'edit'])->name('edit');
        Route::put('/{location}', [LocationController::class, 'update'])->name('update');
        Route::delete('/{location}', [LocationController::class, 'destroy'])->name('destroy');
        Route::get('/{location}/users', [LocationController::class, 'manageUsers'])->name('users');
        Route::post('/{location}/add-user', [LocationController::class, 'addUser'])->name('add-user');
        Route::delete('/{location}/remove-user', [LocationController::class, 'removeUser'])->name('remove-user');
    });
    
    // Home redirect - use HomeController for consistency
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});
