<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IndexController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Index', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });


Route::get('/', [IndexController::class, 'index'])->name('welcome');
Route::get('/services/{service}', [IndexController::class, 'serviceShow'])->name('serviceShow');

// New admin route group
Route::group(['as' => 'dashboard.', 'middleware' => ['auth'], 'prefix' => 'dashboard'],function () {
    Route::get('/', [DashboardController::class, 'index']) ;
    Route::get('servicesList', [DashboardController::class, 'servicesList'])->name('servicesList');
    
    // Add your admin routes here
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Admin/Dashboard');
    // })->name('admin.dashboard');
     
    // Example: Admin profile routes
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('admin.profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('admin.profile.update');
});

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
