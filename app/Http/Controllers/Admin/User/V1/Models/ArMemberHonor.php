<?php

namespace App\Http\Controllers\Admin\User\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor
 *
 * @property int $hid
 * @property int $cid 公司ID
 * @property int $uid 用户UID
 * @property int $xid 荣誉ID
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereHid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\User\V1\Models\ArMemberHonor whereXid($value)
 * @mixin \Eloquent
 */
class ArMemberHonor extends Model
{
    protected $connection = 'ar';
    public $table = 'news_user_honour';
    protected $primaryKey = 'xid';
}
