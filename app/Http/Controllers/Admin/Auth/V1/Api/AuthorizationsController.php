<?php

namespace App\Http\Controllers\Admin\Auth\V1\Api;

use App\Http\Controllers\Admin\Auth\V1\Request\AuthorizationRequest;
use App\Http\Controllers\Admin\Auth\V1\Request\SocialBindRequest;
use Illuminate\Support\Facades\Cookie;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Log;

class AuthorizationsController extends Controller
{
    public function store(AuthorizationRequest $request, User $user)
    {
        $query = $user->query();
        $username = $request->username;

        $user = $query->whereHas('roles', function ($q) {
            $q->where('name', '=', 'super-admin');
        })->where('phone', '=', $username)->first();

        if ($user) {
            //管理员登陆 使用短信+验证码登陆
            $verifyData = \Cache::get($request->verification_key);

            if (!$verifyData) {
                return $this->response->error('验证码已失效', 422);
            }

            /**
             * hash_equals 防止时序攻击
             */
            if (!hash_equals($verifyData['code'], $request->verification_code)) {
                // 返回401
                return $this->response->errorUnauthorized('验证码错误');
            }
        }


        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        $user = Auth::guard('api')->user();
        if ($user->tower_access_token && $user->tower_refresh_token) {
            event(new Login($user, false));
        }

        activity('login')->causedBy($user)->log('登陆成功');

        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    public function customerLogin(Request $request, Customer $customer)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;

        $credentials['password'] = $request->password;

        if (!$token = Auth::guard('customer')->attempt($credentials)) {
            return $this->response->errorUnauthorized('用户名或密码错误');
        }

        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ])->setStatusCode(201);
    }

    /**
     * 在可刷新的时间范围内，换取新token(旧token过期也可以)
     * @return mixed
     */
    public function update()
    {
        $token = Auth::guard('api')->refresh();
        return $this->respondWithToken($token);
    }

    /**
     * 删除token，用户退出登录，将当前token禁用
     * 返回204 无需返回内容
     * @return mixed
     */
    public function destroy()
    {
        Auth::guard('api')->logout();
        activity('logout')->causedBy($this->user())->log('用户登出');
        return $this->response->noContent();
    }

    protected function respondWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60
        ]);
    }

    public function bind(SocialBindRequest $request, User $user)
    {
        //管理员登陆 使用短信+验证码登陆
        $verifyData = \Cache::get($request->verification_key);

        if (!$verifyData) {
            return $this->response->error('验证码已失效', 422);
        }

        /**
         * hash_equals 防止时序攻击
         */
        if (!hash_equals($verifyData['code'], $request->verification_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $query = $user->query();
        $DBUser = $query->where('phone', '=', $verifyData['phone'])->first();
        if (!$DBUser) {
            return $this->response->error('您还未注册，请联系管理员，注册用户！');
        }

        Log::info('bind_openid', [Cookie::get('openid')]);
        $query->where('id', '=', $DBUser->id)->update(['weixin_openid' => Cookie::get('openid')]);

        return $this->response->noContent();

    }
}
