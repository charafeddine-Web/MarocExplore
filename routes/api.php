<?php

use App\Http\Controllers\DestinationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItineraryController;
use App\Http\Controllers\FavoriteItineraryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/itineraries', [ItineraryController::class, 'index']);

    Route::post('/itineraries', [ItineraryController::class, 'store']);

    Route::get('/itineraries/{id}', [ItineraryController::class, 'show']);

    Route::put('/itineraries/{id}', [ItineraryController::class, 'update']);

    Route::delete('/itineraries/{id}', [ItineraryController::class, 'destroy']);

//FavoriteItinerary
    Route::post('/itineraries/{id}/wishlist', [FavoriteItineraryController::class, 'addToWishlist']);
    Route::delete('/itineraries/{id}/wishlist', [FavoriteItineraryController::class, 'removeFromWishlist']);

//  destination

    Route::get('/destinations', [DestinationController::class, 'index']);
    Route::post('/destinations', [DestinationController::class, 'store']);
    Route::get('/destinations/{id}', [DestinationController::class, 'show']);
    Route::put('/destinations/{id}', [DestinationController::class, 'update']);
    Route::delete('/destinations/{id}', [DestinationController::class, 'destroy']);
});




