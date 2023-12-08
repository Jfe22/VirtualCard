<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }


    //test code, doesnt work?
    public function index(User $user)
    {
      return $user->user_type == "A";
    }

    //endpioints que ainda nao foram implementados
    public function view(User $user, User $model)
    {
      return $user->user_type == "A" || $user->id == $model->id;
    }

    public function update(User $user, User $model)
    {
      return $user->user_type == "A" || $user->id == $model->id;
    }

    public function updatePassword(User $user, User $model)
    {
      return $user->id == $model->id;
    }
}
