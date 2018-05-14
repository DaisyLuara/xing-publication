<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Log;
use App\Models\User;

class TowerLoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider(Request $request)
    {
        $redirectUrl = config('services')['tower']['redirect'] . '?' . 'id=' . $request->id;
        return Socialite::driver('tower')->stateless()->redirectUrl($redirectUrl)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        Log::info('request all', $request->all());
        $user_id = $request->id;
        $request->offsetUnset('id');
        /* @var \Laravel\Socialite\Two\User $tower_user */
        $tower_user = Socialite::driver('tower')
            ->stateless()
            ->user();
        Log::info('get user from tower', ['tower_user' => $tower_user]);
        User::where('id', '=', $user_id)->update(
            [
                'tower_access_token' => $tower_user->token,
                'tower_refresh_token' => $tower_user->refreshToken,
            ]
        );
    }
}
