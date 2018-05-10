<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\TopicReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function created(Reply $reply)
    {
        $topic = $reply->topic;
        $topic->increment('reply_count', 1);

        // 如果评论的作者不是话题的作者，才需要通知
        // 自己评论自己是不需要通知的
        if (!$reply->user->isAuthorOf($topic)) {
            $topic->user->notify(new TopicReplied($reply));
        }
    }

    public function creating(Reply $reply)
    {
        //用户内容过滤
        //使用的是 HTMLPurifier for Laravel 5
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count', 1);
    }
}