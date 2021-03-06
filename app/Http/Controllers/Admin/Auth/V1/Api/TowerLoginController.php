<?php

namespace App\Http\Controllers\Admin\Auth\V1\Api;

use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Cookie;
use Log;

class TowerLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        $cookieDomain = env('APP_ENV') == 'production' ? '.xingstation.com' : '.newgls.cn';
        setcookie('user_id', $request->id, time() + 1000, '/', $cookieDomain);
        return Socialite::driver('tower')->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        /* @var \Laravel\Socialite\Two\User $tower_user */
        $tower_user = Socialite::driver('tower')
            ->stateless()
            ->user();
        Log::info('get user from tower', ['tower_user' => $tower_user]);
        User::where('id', '=', Cookie::get('user_id'))->update(
            [
                'tower_access_token' => $tower_user->token,
                'tower_refresh_token' => $tower_user->refreshToken,
            ]
        );

        return redirect(env('TOWER_TEAM_REDIRECT'));
    }
}
