<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PeramalanController;
use App\Http\Controllers\PeramalanHoltsController;
use App\Http\Controllers\PeramalanWinterController;
use App\Http\Controllers\ProduksiController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [AuthController::class, 'index'])->name('/');
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['auth']], function () {
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::prefix('produksi')->name('.produksi')->group(function () {
        Route::get('/karet-kering', [ProduksiController::class, 'karetKering'])->name('.karetKering');
        Route::get('/minyakSawit', [ProduksiController::class, 'minyakSawit'])->name('.minyakSawit');
        Route::get('/bijiSawit', [ProduksiController::class, 'bijiSawit'])->name('.bijiSawit');
        Route::get('/teh', [ProduksiController::class, 'teh'])->name('.teh');
        Route::get('/gulaTebu', [ProduksiController::class, 'gulaTebu'])->name('.gulaTebu');
        Route::get('/import', [ProduksiController::class, 'import'])->name('.import');
        Route::post('/actionImport', [ProduksiController::class, 'actionImport'])->name('.actionImport');
        Route::post('/editProduksi', [ProduksiController::class, 'editProduksi'])->name('.editProduksi');
        Route::get('/delete/{id}', [ProduksiController::class, 'delete'])->name('.delete');

    });
    Route::prefix('peramalanHolts')->name('.peramalanHolts')->group(function () {
        Route::get('/index/{category}', [PeramalanHoltsController::class, 'index'])->name('.index');
        Route::get('/hitungHolts/{category}', [PeramalanHoltsController::class, 'hitungHolts'])->name('.hitungHolts');
        Route::get('/chartHolts/{category}', [PeramalanHoltsController::class, 'chartHolts'])->name('.chartHolts');
        Route::get('/karet-kering', [PeramalanHoltsController::class, 'karetKering'])->name('.karetKering');
        Route::get('/minyakSawit', [PeramalanHoltsController::class, 'minyakSawit'])->name('.minyakSawit');
        Route::get('/bijiSawit', [PeramalanHoltsController::class, 'bijiSawit'])->name('.bijiSawit');
        Route::get('/teh', [PeramalanHoltsController::class, 'teh'])->name('.teh');
        Route::get('/gulaTebu', [PeramalanHoltsController::class, 'gulaTebu'])->name('.gulaTebu');
    });
    Route::prefix('peramalanWinter')->name('.peramalanWinter')->group(function () {
        Route::get('/index/{category}', [PeramalanWinterController::class, 'index'])->name('.index');
        Route::get('/hitungWinter/{category}', [PeramalanWinterController::class, 'hitungWinter'])->name('.hitungWinter');
        Route::get('/chartWinters/{category}', [PeramalanWinterController::class, 'chartWinters'])->name('.chartWinters');
        Route::get('/karet-kering', [PeramalanWinterController::class, 'karetKering'])->name('.karetKering');
    });
    Route::prefix('peramalan')->name('.peramalan')->group(function () {
        Route::get('/karet-kering', [PeramalanController::class, 'karetKering'])->name('.karetKering');
    });
});

