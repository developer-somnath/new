<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class Webservices extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'device_token'=>'required',
            'password'=>'required'
        ]);
 
        if($validator->fails()):
            return response()->json([
                'status'=>FALSE,
                'message'=>$validator->errors(),
                'data' => []
            ],401);
        else:
            try{
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'device_token' => $request->device_token,
                ]);
                $accessToken = $this->generateJWT($user->id, $request->email, $request->device_token);
                User::where('id',$user->id)->update([
                        'app_access_token'=>$accessToken,
                        'device_token'=>$request->device_token,
                ]);
                $this->data['userDetails']=User::select('name','email')->find($user->id)->first();
                $this->data['token']=[
                    'type'=>'Bearer',
                    'accesToken'=>$accessToken,
                    'expireTime'=>time() + (30 * 24 * 60 * 60)
                ];
                return response()->json([
                    'status' => TRUE,
                    'message' => 'Registration successfull !',
                    'data'      => $this->data
                ], 200);
            }catch(\Exception $e){
                // print $e->getMessage(); die;
                return response()->json([
                    'status' => FALSE,
                    'message' => 'Oops Sank! Something went terribly wrong !',
                    'data'      => []
                ], 500);
            }
            // return response()->json($request->all(),200);
        endif;
        
    }

    private function generateJWT($userId, $email, $deviceToken)
    {
       $token = [
            'id'            =>$userId,
            'email'         =>$email,
            'deviceToken'   =>$deviceToken,
            'expireTime'    => time() + (30 * 24 * 60 * 60)
       ];
       return  JWT::encode($token, config('jwt.key'),'HS256');
    }

    public function profileDetails(Request $request)
    {
        $this->data['userDetails']=User::select('name','email')->find(Auth::guard('api')->user()->id)->first();
        return response()->json([
                    'status'=>FALSE,
                    'message'=>'Data available!!',
                    'data' => $this->data
                ],401);

    }
    
}
