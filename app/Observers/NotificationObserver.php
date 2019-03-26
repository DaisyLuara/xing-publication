<?php

namespace App\Observers;


use App\Models\User;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Notifications\DatabaseNotification;

class NotificationObserver
{
    /**
     * Handle the database notification "created" event.
     *
     * @param DatabaseNotification $databaseNotification
     */
    public function saving(DatabaseNotification $databaseNotification)
    {
        try {
            if ($databaseNotification->notifiable_type == 'App\Models\User') {
                /** @var User $user */
                $user = $databaseNotification->notifiable;
                if ($user->weixin_openid) {
                    $app = Factory::officialAccount(config('wechat.official_account'));
                    $content = $databaseNotification->data;
                    if ($content && $content['reply_content']) {
                        $message = new Text($content['reply_content']);
                        $app->customer_service->message($message)->to($user->weixin_openid)->send();
                    }
                }
            }
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'production') {
                ding()->with('other')->text('发送微信消息出错：'.$e->getMessage());
            }
        }
    }

}
