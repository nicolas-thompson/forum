<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the given profile.
     *
     * @param \App\User $user
     * @param \App\User $signedInUser
     */
    public function update(User $user, User $signedInUser,Thread $thread)
    {
        return $signedInUser->id == $user->id;
    }
}
