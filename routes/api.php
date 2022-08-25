<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VenteController;

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
    Route::post('/ventes',[VenteController::class,'save']);
    Route::put('/produits/{id}',[ProduitController::class,'update']);
    Route::delete('/produits/{id}',[ProduitController::class,'destroy']);
    Route::post('/logout',[AuthController::class,'logout']);
});