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
            header('Content-type: application/json');
            if(is_null($request->bearerToken())):
                $response = [
                    'status' => FALSE,
                    'message' => 'Authorization token not present in the request !',
                    'data'      => (object)[]
                   ];
                http_response_code(401);
                print json_encode($response);
                exit;
            endif;
            try{
                $tokenPayload = JWT::decode($request->bearerToken(), new Key(config('jwt.key'), 'HS256'));
                $userDetails = \App\Models\User::where('id',$tokenPayload->id)->where('app_access_token',$request->bearerToken())->first();
                if(!is_null($userDetails)):
                    if ($tokenPayload->expireTime > time()) :
                        return $tokenPayload;
                    else:
                        $response =  (object)[
                            'status' => FALSE,
                            'message' => 'Token has been expired!',
                            'data'      => (object)[]
                        ];
                        http_response_code(440);
                        print json_encode($response);
                        exit;
                    endif;
                else:
                    $response =  (object)[
                        'status' => FALSE,
                        'message' => 'Invalid Authorization token!',
                        'data'      => (object)[]
                    ];
                    http_response_code(401);
                    print json_encode($response);
                    exit;
                endif;
            } catch(\Exception $e){
                $response =  [
                    'status' => FALSE,
                    'message' => 'Oops Sank! Something went terribly wrong !',
                    'data'      => (object)[]
                ];
                http_response_code(401);
                print json_encode($response);
                exit;
            }
        });
    }
    
}
