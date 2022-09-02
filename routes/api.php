<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\MouvementController;
use App\Http\Controllers\FamilleController;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\ClientController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Public Routes
//Route::resource('produits', ProduitController::class);
Route::get('/produits',[ProduitController::class, 'index']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::get('/produits/{id}',[ProduitController::class, 'show']);
Route::get('/produits/search/{name}',[ProduitController::class, 'search']);


// Protected Routes
Route::group(['middleware' => ['auth:sanctum']],function()
{
    Route::post('/produits',[ProduitController::class,'save']);
    Route::post('/approsdepot',[MouvementController::class,'approdepot']);
    Route::post('/approsboutique',[MouvementController::class,'ravitaillerboutique']);
    Route::post('/ventes',[VenteController::class,'save']);
    Route::post('/clients',[ClientController::class,'save']);
    Route::post('/depots',[DepotController::class,'save']);
    Route::delete('/ventes/{id}',[VenteController::class,'delete']);
    Route::delete('/clients/{id}',[ClientController::class,'delete']);
    Route::put('/produits/{id}',[ProduitController::class,'update']);
    Route::put('/familles/{id}',[FamilleController::class,'update']);
    Route::delete('/produits/{id}',[ProduitController::class,'delete']);
    Route::delete('/familles/{id}',[FamilleController::class,'delete']);
    Route::post('/familles',[FamilleController::class,'save']);
    Route::post('/logout',[AuthController::class,'logout']);
});