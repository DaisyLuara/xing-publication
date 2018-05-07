<?php

namespace App\Http\Controllers\Api\V1;


use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\V1\CaptchaRequest;
use App\Models\User;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder, User $user)
    {
        $phone = $request->phone;

        //管理员登陆 需要图形验证码
        $query = $user->query();

        $user = $query->whereHas('roles', function ($q) {
            $q->where('name', '=', 'super-admin');
        })->where('phone', '=', $phone)->first();

        if(!$user){
           return $this->response->noContent();
        }

        $key = 'captcha-' . str_random(15);
        /**
         * 保护短信接口 不被代理IP攻击
         */

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
