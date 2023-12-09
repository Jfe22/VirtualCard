<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    //
    public function index() {
      return UserResource::collection(User::all());
    }

    public function show_me(Request $request) {
      return new UserResource($request->user());
    }

    public function show(User $user) {
      return new UserResource($user);
    }

    public function store(Request $request) {
      $user = User::create($request->all()); //request->validaded 
      return new UserResource($user);
    }

    public function update(Request $request, User $user) {
      $user->update($request->all());
      return new UserResource($user);
    }

    public function delete(User $user) {
      $user->delete();
      return response()->json();
    }
    



}
