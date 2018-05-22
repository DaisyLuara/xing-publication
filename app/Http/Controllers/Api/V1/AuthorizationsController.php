<?php

namespace App\Http\Controllers\Api\V1;

use Auth;
use App\Http\Requests\Api\V1\AuthorizationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Login;

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

        event(new Login($this->user(), false));

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
}
