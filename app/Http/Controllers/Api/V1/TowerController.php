<?php

namespace App\Http\Controllers\Api\V1;

use App\Transformers\UserTransformer;
use GuzzleHttp\Client;

class TowerController extends Controller
{
    public function refresh()
    {
        $user = $this->user();

        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $user->tower_access_token,
            ],
            'json' => [
                'refresh_token' => $user->tower_refresh_token,
                'grant_type' => 'refresh_token',
            ]
        ];

        $url = 'https://tower.im/oauth/token';

        $http_client = new Client();
        $response = $http_client->request('POST', $url, $options);

        $userToken = json_decode($response->getBody(), true);
        $user->update(['tower_access_token' => $userToken['access_token'], 'tower_refresh_token' => $userToken['refresh_token']]);

        return $this->response->item($this->user(), new UserTransformer());
    }
}
