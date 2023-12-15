<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

use App\Http\Requests\StoreUpdateUserRequest;

class UserController extends Controller
{
    //
    public function index() {
      return UserResource::collection(User::all());
    }

    public function show_me(Request $request) {
      error_log($request);
      return new UserResource($request->user());
    }

    public function show(User $user) {
      return new UserResource($user);
    }

    public function store(StoreUpdateUserRequest $request) {
      $user = User::create($request->validated()); 
      return new UserResource($user);
    }

    public function update(StoreUpdateUserRequest $request, User $user) {
      $user->update($request->validated());
      return new UserResource($user);
    }

    public function destroy(User $user) {
      $user->delete();
      return response()->json();
    }

    public function update_password(UpdatePasswordUserRequest $request, User $user) {
      $user->password = bcrypt($request->validated()['password']);
      $user->save();
      return new UserResource($user);
    }
    



}
