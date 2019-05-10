<?php

namespace App\Http\Controllers\Admin\Common\V3\Models;

use App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Common\V3\Models\Confession
 *
 * @property int $id
 * @property int $wx_user_id
 * @property string $z
 * @property string $name 告白对象姓名
 * @property string $phone
 * @property int|null $media_id 照片
 * @property string $message 告白留言
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Confession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Confession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|Confession query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereWxUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereZ($value)
 * @mixin \Eloquent
 * @property int|null $qiniu_id 七牛ID
 * @property string|null $record_id 录音ID
 * @property string $belong 游戏名称
 * @property-read \App\Http\Controllers\Admin\Resource\V1\Models\ActivityMedia|null $media
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereQiniuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereRecordId($value)
 * @property string $utm_campaign 游戏名称
 * @method static \Illuminate\Database\Eloquent\Builder|Confession whereUtmCampaign($value)
 */
class Confession extends Model
{
    protected $fillable = [
        'wx_user_id',
        'z',
        'name',
        'phone',
        'media_id',
        'message',
        'qiniu_id',
        'record_id',
        'utm_campaign',
    ];

    public function media()
    {
        return $this->belongsTo(ActivityMedia::class, 'media_id', 'id');
    }

}
