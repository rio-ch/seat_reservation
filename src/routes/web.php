<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReservationController;

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
Route::middleware(['auth'])->group(function () {
    /*//新規予約作成
    Route::post('/store', [ReservationController::class, 'store'])->middleware(['auth', 'verified'])->name('reservation.store');
//予約キャンセル
    Route::post('/cancel', [ReservationController::class, 'cancel'])->middleware(['auth', 'verified'])->name('reservation.cancel');
//今日の座席一覧表示
    Route::get('/reservations', [ReservationController::class, 'index'])->middleware(['auth', 'verified'])->name('reservation.index');
//指定した日付の座席一覧を表示
    Route::post('/reservations', [ReservationController::class, 'store'])->middleware(['auth', 'verified'])->name('reservation.store');

    Route::get('/', function () {
        return view('welcome');
    */
    Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::delete('/reservations/{seat}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
