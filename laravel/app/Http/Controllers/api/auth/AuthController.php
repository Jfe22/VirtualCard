<?php

namespace App\Http\Controllers\api\auth;

use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
  //
  private function passportAuthenticationData($username, $password) {
    return [
      'grant_type' => 'password',
      'client_id' => env('PASSPORT_PASSWORD_GRANT_ID'),
      'client_secret' => env('PASSPORT_PASSWORD_GRANT_SECRET'),
      'username' => $username,
      'password' => $password,
      'scope' => ''
    ];
  }

  public function login(Request $request)
  { 
    try {
      //$url = env('PASSPORT_URL') . '/oauth/token';
      /*$response = Http::post($url, $this->passportAuthenticationData($request->username, $request->password));
      $response = $http->post($url, $bodyHttpRequest);
      $errorCode = $response->getStatusCode();*/
      request()->request->add(
      $this->passportAuthenticationData($request->username, $request->password));
      //dd($request->username, $request->password);
     // dd( env('PASSPORT_URL') . '/oauth/token');
      $request = Request::create(
      env('PASSPORT_URL') . '/oauth/token', 'POST');
      //dd($request);
      $response = Route::dispatch($request);
      //dd($request);
      $errorCode = $response->getStatusCode();
      //dd($request);
      $auth_server_response = json_decode((string) $response->content(), true);
      //error_log($request);
      return response()->json($auth_server_response, $errorCode);
    }
    catch (\Exception $e) {
      dd($e);
        return response()->json('Authentication has failed!', 401);
    }
  }
    
  public function logout(Request $request)
  {
    $accessToken = $request->user()->token();
    $token = $request->user()->tokens->find($accessToken);
    $token->revoke();
    $token->delete();
    return response(['msg' => 'Token revoked'], 200);
  }
}
