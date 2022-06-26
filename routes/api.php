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
});
Route::middleware('auth:api')->group(function() {
    $responseFromBoot = Auth::guard('api')->user();
    if(!is_null($responseFromBoot) && isset($responseFromBoot->statusCode)):
        header('Content-type: application/json');
        http_response_code($responseFromBoot->statusCode);
        print json_encode([
            'status'    =>$responseFromBoot->status,
            'message'   =>$responseFromBoot->message,
            'data'      =>$responseFromBoot->data
        ]);
        exit;
    endif;
    Route::post('user-profile', [Webservices::class, 'profileDetails']);
  
});
