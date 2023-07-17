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
      

        $credentials = $request->only('email', 'password');

        if($request->email == '' && empty($request->email) && !isset($request->email)){
          return response()->json(['message' => 'UserName is required'],201);
        }
        if($request->password == '' && empty($request->password) && !isset($request->password)){
          return response()->json(['message' => 'Password is required'],201);
        }
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('example')->accessToken;

            return response()->json(['token' => $token], 200);
        }

        return response()->json(['message' => 'Unauthorized'], 401);
      
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
    public function userLogout(Request $request)
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
