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
    public function created(DatabaseNotification $databaseNotification)
    {
        try {
            if ($databaseNotification->notifiable_type == 'App\Models\User') {
                /** @var User $user */
                $user = $databaseNotification->notifiable;
                if ($user->weixin_openid) {
                    $app = Factory::officialAccount(config('wechat.official_account.default'));


                    $content = $databaseNotification->data;
                    if ($content && $content['reply_content']) {

                        $app->template_message->send([
                            'touser' => $user->weixin_openid,
                            'template_id' => config('wechat.official_account_template_id.notification'),
                            'url' => null,
                            'data' => [
                                'first' => $user->name,
                                'keyword1' => $content['reply_content'],
                                'keyword2' => '无',
                                'keyword3' => '无',
                                'keyword4' => '无',
                                'keyword5' => '数据中台',
                                'remark' =>'',
                            ]
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            if (env('APP_ENV') == 'production') {
                ding()->with('other')->text('发送微信消息出错：' . $e->getMessage());
            }
//            else if ($databaseNotification->notifiable->weixin_openid == 'oP41x03B8-rG3VnWAMqvFFVQr2G0') {
//                abort(500, $e->getMessage());
//            }
        }
    }

}
