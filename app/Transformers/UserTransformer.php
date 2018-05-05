<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles', 'permissions'];

    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar,
            'phone' => substr_replace($user->phone, '****', 3, 4),
            'ar_user_id' => $user->ar_user_id,
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }

    public function includePermissions(User $user)
    {
        return $this->collection($user->getAllPermissions(), new PermissionTransformer());
    }
}