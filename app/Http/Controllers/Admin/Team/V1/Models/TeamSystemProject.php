<?php

namespace App\Http\Controllers\Admin\Team\V1\Models;


use App\Models\Model;
use App\Models\User;

/**
 * App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject
 *
 * @property int $id
 * @property string $name 平台项目名称
 * @property int $applicant 申请人
 * @property string $status 1:申请中,2:已分配,3:已驳回
 * @property string|null $remark 备注
 * @property string|null $reject_message 驳回意见
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereApplicant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereRejectMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Team\V1\Models\TeamSystemProject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TeamSystemProject extends Model
{
    protected $fillable = [
        'name',
        'applicant',
        'status',
        'remark',
        'reject_message'
    ];

    public static $statusMapping = [
        '1' => '申请中',
        '2' => '已分配',
        '3' => '已驳回'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'applicant', 'id');
    }
}