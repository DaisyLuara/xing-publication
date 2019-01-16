<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Auth;

class ArMemberSession extends Authenticatable implements JWTSubject
{
    protected $connection = 'ar';
    public $table = 'news_sessions';
    protected $primaryKey = 'uid';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'lastip', 'lastactivity',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
