<?php

namespace App\Http\Controllers\Api\V1;


use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
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
        return Socialite::driver('tower')->stateless()->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('tower')->stateless()->user();
        Log::info('get user from tower', ['user' => $user]);
    }
}
