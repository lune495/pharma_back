<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\ApprovisionnementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\ProformaController;
use App\Http\Controllers\DepotController;

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
    Route::get('/stat_stock_par_depot/{depot_id}', [DepotController::class,'getMouvementsProduits']);
    Route::get('/vente/generate-pdf/{id}', [VenteController::class,'generatePDF']);
    Route::get('/stat_modules', [VenteController::class,'statModules']);
    Route::get('/vente/ticket-retour-pdf/{id}', [VenteController::class,'generatePDFRetour']);
    Route::get('/vente/situation', [VenteController::class,'generatePDF2']);
    Route::get('/approvisionnementpdf/{id}', [ApprovisionnementController::class,'genereallPDf']);
    Route::get('/getAllCaProduit/{from?}/{to?}', [VenteController::class,'generateCaProduits']);
    Route::get('/getAllQteAppro/{from?}/{to?}', [VenteController::class,'generateQteAppro']);
    Route::get('/export-produits',[ProduitController::class,'exportProduit']);
    Route::get('/getVentesParProduitJuin', [ProduitController::class, 'getVentesParProduitJuin']);
    Route::get('/test',[VenteController::class,'Notif']);
    // Route::get('/', function () {
    //     return view('welcome');
    // });
//});