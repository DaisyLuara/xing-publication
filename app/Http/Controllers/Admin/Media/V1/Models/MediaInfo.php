<?php

namespace App\Http\Controllers\Admin\Media\V1\Models;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Media\V1\Models\MediaInfo
 *
 * @property int $id
 * @property int $media_id 媒体ID
 * @property string|null $name 文档名称
 * @property string $type 文档类型，节目运营文档 project_operation
 * @property string|null $date 上传日期
 * @property int $recorder_id 上传者
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media $media
 * @property-read \App\Models\User $recorder
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereRecorderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Media\V1\Models\MediaInfo withoutTrashed()
 * @mixin \Eloquent
 */
class MediaInfo extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'type',
        'media_id',
        'date',
        'recorder_id'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorder_id', 'id');
    }
}
