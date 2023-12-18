<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
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
