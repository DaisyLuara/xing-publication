<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    public function destroy(User $user, Reply $reply)
    {
        //话题作者 和 评论作者 可以删除 评论
        return $user->isAuthorOf($reply) || $user->isAuthorOf($reply->topic);
    }
}