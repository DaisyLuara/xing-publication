<?php


namespace App\Providers;

use Illuminate\Support\Arr;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class TowerProvider extends AbstractProvider implements ProviderInterface
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
        return $this->buildAuthUrlFromBase('https://tower.im/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://tower.im/oauth/token';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $userUrl = 'https://tower.im/user?access_token=' . $token;

        $response = $this->getHttpClient()->get(
            $userUrl, $this->getRequestOptions()
        );

        $user = json_decode($response->getBody(), true);

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'], 'nickname' => $user['login'], 'name' => Arr::get($user, 'name'),
            'email' => Arr::get($user, 'email'), 'avatar' => $user['avatar_url'],
        ]);
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
}
