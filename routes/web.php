<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SedeController;

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

Route::any('/', function () {
    return redirect()->route('login');
})->name('landing.index');

Route::middleware('auth')->prefix('dashboard')->group(function () {

    //Routes must have the following structure to work with the dialogs:
    // index: /item
    // store: /item
    // update: /item/{id}
    // destroy: /item/{id}
    // destroyPermanent: /item/{id}/permanent
    // restore: /item/{id}/restore
    // exportExcel: /item/export-excel

    Route::get('/sedes',[SedeController::class, 'index'])->name('dashboard.sedes');
    Route::post('/sedes/load-items',[SedeController::class, 'loadItems'])->name('dashboard.sedes.load-itmes');
    Route::post('/sedes',[SedeController::class, 'store'])->name('dashboard.sedes.store');
    Route::put('/sedes/{sede}',[SedeController::class, 'update'])->name('dashboard.sedes.update');
    Route::delete('/sedes/{sede}',[SedeController::class, 'destroy'])->name('dashboard.sedes.destroy');
    Route::delete('/sedes/{sede}/permanent',[SedeController::class, 'destroyPermanent'])->name('dashboard.sedes.destroyPermanent');
    Route::post('/sedes/{sede}/restore',[SedeController::class, 'restore'])->name('dashboard.sedes.restore');
    Route::get('/sedes/export-excel',[SedeController::class, 'exportExcel'])->name('dashboard.sedes.exportExcel');

    
});

require __DIR__ . '/auth.php';

