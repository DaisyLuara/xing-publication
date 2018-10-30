<?php

namespace App\Http\Controllers\Admin\Auth\V1\Api;

use App\Http\Controllers\Admin\Auth\V1\Request\AuthorizationRequest;
use App\Http\Controllers\Admin\Auth\V1\Request\SocialBindRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

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
        Cookie::queue(Cookie::forget('jwt_token', '', 'jingfree.top'));
        Cookie::queue(Cookie::forget('jwt_ttl', '', 'jingfree.top'));
        Cookie::queue(Cookie::forget('jwt_begin_time', '', 'jingfree.top'));
        Cookie::queue(Cookie::forget('permissions', '', 'jingfree.top'));
        Cookie::queue(Cookie::forget('user_info', '', ''));
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
            return $this->response->error('您还未注册，请联系管理员，注册用户！', 403);
        }

        User::where('id', '=', $DBUser->id)->update(['weixin_openid' => $request->openid]);

        return $this->response->noContent();

    }

    public function systemSkip(Request $request)
    {
        $token = Auth::guard('api')->refresh();
        $ttl = Auth::guard('api')->factory()->getTTL() * 60;
        $beginTime = strtotime(Carbon::now()->toDateTimeString());
        Cookie::queue(Cookie::make('jwt_token', $token, '', '', env('DOMAIN_SERVER_NAME')));
        Cookie::queue(Cookie::make('jwt_ttl', $ttl, '', '', env('DOMAIN_SERVER_NAME')));
        Cookie::queue(Cookie::make('jwt_begin_time', $beginTime, '', '', env('DOMAIN_SERVER_NAME')));
        Cookie::queue(Cookie::make('permissions', $request->permissions, '', '', env('DOMAIN_SERVER_NAME')));
        Cookie::queue(Cookie::make('user_info', $request->user_info, '', '', env('DOMAIN_SERVER_NAME')));
        if ($request->type == 'ad') {
            return redirect(env('PUBLICATION_URL'));
        } else {
            return redirect(env('PROCESS_URL'));
        }

    }
}
