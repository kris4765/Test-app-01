<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Login'); // This renders resources/js/Pages/Home.vue
});


Route::middleware('guest')->group(function () {
    Route::get('/login', fn() => Inertia::render('Login'))->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});


Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');


// Route::get('/dashboard', fn () => Inertia::render('Dashboard'));
// Route::get('/users', fn () => Inertia::render('Users'));
// Route::get('/settings', fn () => Inertia::render('Settings'));
// Route::get('/db_test', fn () => Inertia::render('DBproftest'));



Route::middleware('auth')->group(function () {
    Route::get('/home', fn() => Inertia::render('Home'))->name('home');
    Route::get('/dashboard', fn() => Inertia::render('Dashboard'))->name('dashboard');
    Route::get('/users', fn() => Inertia::render('Users'));
    Route::get('/settings', fn() => Inertia::render('Settings'));
    Route::get('/db_test', fn() => Inertia::render('DBproftest'));
});





// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

// require __DIR__.'/auth.php';
