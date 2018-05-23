<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\UserTransformer;
use Laravel\Socialite\Facades\Socialite;

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
