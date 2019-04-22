<?php

namespace App\Http\Controllers\Admin\Guest\V1\Models;

use App\Models\Model;

/**
 * Class GuestMobile
 * @package App\Http\Controllers\Admin\Guest\V1\Models
 * @property int $id
 * @property string $mobile
 * @property string $ip
 * @property string $city
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class GuestMobile extends Model
{
    protected $fillable = [
        'mobile', 'ip', 'city'
    ];
}
