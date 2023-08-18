<?php

use App\Http\Controllers\CandidateController;
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

Route::get('/', fn() => '')->name('login');
Route::post('cadastrar', [CandidateController::class, 'store'])->name('candidates.store');
Route::middleware(['auth', 'auth.session'])->group(function(): void {
    Route::get('vagas', fn() => '')->name('pages.vacancies');
    Route::get('perfil', fn() => '')->name('pages.profile');
    Route::patch('perfil', [CandidateController::class, 'update'])->name('pages.profile.update');
    Route::delete('perfil', [CandidateController::class, 'destroy'])->name('pages.profile.destroy');
});
