<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  //
  private function passportAuthenticationData($username, $password) {
    return [
      'grant_type' => 'password',
      'client_id' => env('PASSPORT_CLIENT_ID'),
      'client_secret' => env('PASSPORT_CLIENT_SECRET'),
      'username' => $username,
      'password' => $password,
      'scope' => ''
    ];
  }

  public function login(Request $request)
  {
    try {
      request()->request->add(
      $this->passportAuthenticationData($request->username, $request->password));
      $request = Request::create(
      env('PASSPORT_SERVER_URL') . '/oauth/token', 'POST');
      $response = Route::dispatch($request);
      $errorCode = $response->getStatusCode();
      $auth_server_response = json_decode((string) $response->content(), true);
      return response()->json($auth_server_response, $errorCode);
    }
    catch (\Exception $e) {
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
