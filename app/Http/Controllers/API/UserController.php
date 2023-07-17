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
      dd(123);
      

        $credentials = $request->only('email', 'password');

        if($request->email == '' && empty($request->email) && !isset($request->email)){
          return response()->json(['message' => 'UserName is required'],201);
        }
        if($request->password == '' && empty($request->password) && !isset($request->password)){
          return response()->json(['message' => 'Password is required'],201);
        }
        
        if (auth()->attempt($credentials)) {
            dd($request->email);
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

    public function getCaptchaValues(){
      $values = array('apple', 'strawberry', 'lemon', 'cherry', 'pear');
      $reqId = uniqid();
      $imageExt = 'jpg';
      $imagePath = 'public/captcha/imgs/s3icons/fruit/';
      $imageW = '50';
      $imageH = '52';
      $rand = mt_rand(0, (sizeof($values) - 1));
      shuffle($values);

      $s3Capcha = '<p id="verficationMsg">Verify that you are human, please choose <strong>' . $values[$rand] . "</strong></p>\n";
      $s3Capcha .= '<div id="captchaDiv">';
      for ($i = 0; $i < sizeof($values); $i++) {
          $value2[$i] = mt_rand();
          //$s3Capcha.= '<div><span>' . $values[$i] . ' <input type="radio" name="s3capcha" id="s3capcha" value="' . $value2[$i] . '"><input type="hidden" name="s3capchakey" id="s3capchakey" value="' . $reqId . '"></span><div style="background: url(' . $imagePath . $values[$i] . '.' . $imageExt . ') bottom left no-repeat; width:' . $imageW . 'px; height:' . $imageH . 'px;cursor:pointer;display:none;" class="img" /></div></div>' . "\n";
          $s3Capcha .= '<div><span>' . $values[$i] . ' <input type="radio" name="s3capcha" id="s3capcha" value="' . $value2[$i] . '"></span><div style="background: url(' . $imagePath . $values[$i] . '.' . $imageExt . ') bottom left no-repeat; width:' . $imageW . 'px; height:' . $imageH . 'px;cursor:pointer;display:none;" class="img" /></div></div>' . "\n";
      }
      $s3Capcha .= "</div>";
      $req_rand = mt_rand();

      $s3Capcha .= '<input type="hidden" name="s3capchakey" id="s3capchakey" value="' . $reqId . $req_rand . '">';
      // $this->addCaptcha($reqId . $req_rand, $value2[$rand]);
      session()->put('s3capcha',  $value2[$rand]);
      // $_SESSION['s3capcha'] =
      echo $s3Capcha;
    }

    function addCaptcha($reqId, $captchaValue)
    {

        // global $activitylog;

        $reCpatcha = '';
        $sessionCpatcha = '';
        $type = '';
        $userId = '';

        // $requester_ip = $activitylog->visitorIP();




        $table_name = 'captcha_verification';


        //$sql = "INSERT INTO `$table_name`(dateTime, requester_ip, session_captcha, request_captcha,type,userId, success) VALUES(NOW(), '$requester_ip','$sessionCpatcha', '$reCpatcha', '$type', '$userId',$success)";

        // $this->db->adv($sql);
        // $data = array(
        //     "dateTime" => $this->advDB->now(),
        //     "request_id" => $reqId,
        //     "captcha_value" => $captchaValue,
        //     'requester_ip' => $requester_ip
        // );

        // $id = $this->advDB->insert($table_name, $data);
    }

}
