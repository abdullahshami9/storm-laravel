<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Response;
// use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function loginUser(Request $request)
    {
      if(Auth::guard('api')->check()){

        $input = $request->all();
        Auth::attempt($input);
        $user = Auth::user();

        $token = $user->createToken('example')->accessToken;
        return Response(['status' => 200,'token' => $token],200);
      }
      else{
        return Response(['status' => 201,'message'=>'User does not exist','data' => 'Invalid Parameter']);
      }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getUserDetail()
    {
        if (Auth::guard('api')->check()) {
          // code...
          $user = Auth::guard('api')->user();
          return Response(['data' => $user],200);
        }
        else {
          return Response(['data' => 'UnAuthenticate'],401);
        }
    }

    /**
     * Display the specified resource.
     */
    public function userLogout()
    {
      if (Auth::guard('api')->check()) {

        $accessToken = Auth::guard('api')->user()->token();
        \DB::table('oauth_refresh_tokens')
        ->where('access_token_id',$accessToken->id)
        ->update(['revoked' => true]);
        $accessToken->revoke();
        return Response(['data' => 'UnAuthenticate','message' => 'user logout successfully'],200);
      }
      else{
        return Response([
          'data' => 'UnAuthenticate'
        ],401);
      }

    }


}
