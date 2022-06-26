<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Auth::viaRequest('jwt', function (Request $request) {

            if(is_null($request->bearerToken())):
                return (object)[
                    'status' => FALSE,
                    'message' => 'Authorization token not present in the request !',
                    'statusCode'=>401,
                    'data'      => []
                   ];
            endif;
            try{
                $tokenPayload = JWT::decode($request->bearerToken(), new Key(config('jwt.key'), 'HS256'));
                $userDetails = \App\Models\User::where('id',$tokenPayload->id)->where('app_access_token',$request->bearerToken())->first();
                if(!is_null($userDetails)):
                    if ($tokenPayload->expireTime > time()) :
                        return $tokenPayload;
                    else:
                        return (object)[
                            'status' => FALSE,
                            'message' => 'Token has been expired!',
                            'statusCode'=>440,
                            'data'      => []
                        ];
                    endif;
                else:
                    return (object)[
                        'status' => FALSE,
                        'message' => 'Invalid Authorization token!',
                        'statusCode'=>401,
                        'data'      => []
                       ];
                endif;
            } catch(\Exception $e){
               return (object)[
                'status' => FALSE,
                'message' => 'Oops Sank! Something went terribly wrong !',
                'statusCode'=>500,
                'data'      => []
               ];
            }
        });
    }
    
}
