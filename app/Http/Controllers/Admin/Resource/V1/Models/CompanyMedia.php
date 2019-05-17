<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/9
 * Time: 下午4:58
 */

namespace App\Http\Controllers\Admin\Resource\V1\Models;


use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Http\Controllers\Admin\Resource\V1\Models\CompanyMedia
 *
 * @property int $id
 * @property int $group_id
 * @property int $media_id
 * @property int $status
 * @property int|null $audit_user_id
 * @property-read \App\Models\User|null $auditor
 * @property-read \App\Http\Controllers\Admin\Resource\V1\Models\CompanyMediaGroup $group
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media $media
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia whereAuditUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CompanyMedia whereStatus($value)
 * @mixin \Eloquent
 */
class CompanyMedia extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'group_id',
        'media_id',
        'status',
        'audit_user_id'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(CompanyMediaGroup::class, 'group_id', 'id');
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'audit_user_id', 'id');
    }

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}