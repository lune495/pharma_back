<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProformaController;

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
// Protected Routes
// Route::group(['middleware' => ['auth:sanctum']],function()
// {
    Route::get('/vente/generate-pdf/{id}', [VenteController::class,'generatePDF']);
    Route::get('/proforma/generate-pdf/{id}', [ProformaController::class,'generatePDF']);
    Route::get('/approvisionnementpdf/{id}', [ApprovisionnementController::class,'genereallPDf']);
    Route::get('/export-produits',[ProduitController::class,'exportProduit']);
    // Route::get('/', function () {
    //     return view('welcome');
    // });
//});