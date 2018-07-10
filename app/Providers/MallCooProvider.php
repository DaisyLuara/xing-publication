<?php


namespace App\Providers;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class MallCooProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = [''];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://m.mallcoo.cn/a/open/User/V2/OAuth/BaseInfo', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://openapi10.mallcoo.cn/User/OAuth/v1/GetToken/ByTicket/';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://tower.im/api/v1/user?access_token=' . $token;

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions()
        );

        return json_decode($response->getBody(), true);

    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user, 'data.id'), 'nickname' => Arr::get($user, 'data.attributes.nickname'),
            'email' => Arr::get($user, 'data.attributes.email'),
        ]);
    }

    protected function getTokenFields($code)
    {
        return [
            'client_id' => $this->clientId, 'client_secret' => $this->clientSecret,
            'code' => $code, 'redirect_uri' => $this->redirectUrl,
            'grant_type' => 'authorization_code',
        ];
    }

    /**
     * Get the default options for an HTTP request.
     *
     * @return array
     */
    protected function getRequestOptions()
    {
        return [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ];
    }

    public function refresh($accessToken, $refreshToken)
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'json' => [
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ]
        ];

        $response = $this->getHttpClient()->request('POST', $this->getTokenUrl(), $options);

        return json_decode($response->getBody(), true);
    }
}
