<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserMessagePolicy
{
    use HandlesAuthorization;

    public function view(User $user, UserMessage $message)
    {
        return $user->id === $message->user_id || $user->id === $message->recipient_id;
    }

    public function update(User $user, UserMessage $message)
    {
        return $user->id === $message->recipient_id;
    }

    public function delete(User $user, UserMessage $message)
    {
        return $user->id === $message->user_id || $user->id === $message->recipient_id;
    }

    public function reply(User $user, UserMessage $message)
    {
        return $user->id === $message->recipient_id;
    }
}