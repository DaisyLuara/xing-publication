<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/26
 * Time: 下午6:00
 */

namespace App\Http\Controllers\Admin\Media\V1\Models;


use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Media\V1\Models\ActivityMedia
 *
 * @property int $id
 * @property string $name
 * @property int $size
 * @property string $url
 * @property int $status 0:未通过，1：通过，2：待审核
 * @property int|null $audit_user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereAuditUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia whereUrl($value)
 * @mixin \Eloquent
 */
class ActivityMedia extends Model
{
    protected $fillable = [
        'name',
        'size',
        'url',
        'status',
    ];
}