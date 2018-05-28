<?php

namespace App\Http\Controllers\Admin\Auth\V1\Api;

use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;

class TowerController extends Controller
{
    public function refresh()
    {
        $user = $this->user();

        $userToken = Socialite::driver('tower')
            ->stateless()
            ->refresh($user->tower_access_token, $user->tower_refresh_token);

        $user->update(['tower_access_token' => $userToken['access_token'], 'tower_refresh_token' => $userToken['refresh_token']]);
        return $this->response->item($this->user(), new UserTransformer());
    }
}
