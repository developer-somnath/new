<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Webservices;


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

Route::prefix('auth')->group(function() {
    Route::post('register', [Webservices::class, 'register']);
    Route::post('login', [Webservices::class, 'register']);
    Route::middleware('auth:api')->post('user-profile', [Webservices::class, 'profileDetails']);

});
// Route::middleware('api')->group(function() {
    Route::post('user-profile', [Webservices::class, 'profileDetails']);
// });

