<?php

namespace App\Observers;


use App\Models\User;
use EasyWeChat\Factory;
use Illuminate\Notifications\DatabaseNotification;

class NotificationObserver
{
    /**
     * Handle the database notification "created" event.
     *
     * @param DatabaseNotification $databaseNotification
     */
    public function created(DatabaseNotification $databaseNotification): void
    {
        if (env('APP_ENV') === 'production') {
            try {
                if ($databaseNotification->notifiable_type === 'App\Models\User') {
                    $content = $databaseNotification->data;
                    if ($content && $content['reply_content'] && $content['wechat_notify']) {
                        /** @var User $user */
                        $user = $databaseNotification->notifiable;
                        if ($user->weixin_openid) {
                            $app = Factory::officialAccount(config('wechat.official_account.default'));
                            $app->template_message->send([
                                'touser' => $user->weixin_openid,
                                'template_id' => config('wechat.official_account_template_id.notification'),
                                'url' => null,
                                'data' => [
                                    'first' => $user->name . ' 您有一条新的通知消息',
                                    'keyword1' => $content['reply_content'],
                                    'keyword2' => ' -- ',
                                    'keyword3' => ' -- ',
                                    'keyword4' => ' --',
                                    'keyword5' => '数据中台',
                                    'remark' => '',
                                ]
                            ]);
                        }
                    }
                }
            } catch (\Exception $e) {
                ding()->with('other')->text('发送微信消息出错：' . $e->getMessage());
            }
        }
    }

}
