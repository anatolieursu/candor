<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->name("welcome");

Route::get('/dashboard/{user?}', [\App\Http\Controllers\Controller::class, "viewUserProfile"])->middleware(['auth', 'isVerified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post("/switch", [\App\Http\Controllers\Controller::class, "switchPrivatePublic"])->name("user.switch");
Route::get("/view/{user}", [\App\Http\Controllers\Controller::class, "viewUserProfile"])->name("view.user");
Route::get("/search", [\App\Http\Controllers\Controller::class, "searchUser"])->name("search");

require __DIR__.'/auth.php';
