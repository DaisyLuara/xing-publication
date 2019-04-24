<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\ArModel;

/**
 * App\Http\Controllers\Admin\Point\V1\Models\UserActivation
 *
 * @property int $id
 * @property int $uid
 * @property int $areaid
 * @property int $marketid
 * @property int $oid
 * @property string $res 来源：h5,publick,subk,apps,app
 * @property string $date 时间
 * @property int $clientdate 时间轴
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereMarketid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereRes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\UserActivation whereUid($value)
 * @mixin \Eloquent
 */
class UserActivation extends ArModel
{
    protected $table = 'new_user_oid';

    protected $fillable = [
        'uid',
        'areaid',
        'marketid',
        'oid',
    ];
}

