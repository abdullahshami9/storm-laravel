<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class LoginController extends Controller
{
  public function login()
  {
    // echo view('login');
  //     $credentials = $request->only('email', 'password');
  //
  //     if (Passport::attempt($credentials)) {
  //         // Authentication successful
  //         return redirect()->route('dashboard');
  //     } else {
  //         // Authentication failed
  //         return back()->withInput()->withErrors(['login' => 'Invalid login credentials']);
  //     }

  echo view('login');
}
public function checkLogin(Request $request){
  print_r($request->input());
}
}

  ?>
