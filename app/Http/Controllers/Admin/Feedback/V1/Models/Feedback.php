<?php

namespace App\Http\Controllers\Admin\Feedback\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Feedback\V1\Models\Feedback
 *
 * @property int $id
 * @property string|null $title 标题
 * @property string|null $content 内容
 * @property string $createable_type
 * @property int $createable_id
 * @property int $parent_id 上一级提问/回答
 * @property int $top_parent_id 顶层提问/回答
 * @property int|null $video_media_id 视频文件
 * @property int $status 0 无状态（有parent_id的都为0） 1 待处理 2 已处理
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback[] $childrenFeedback
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $createable
 * @property-read \App\Http\Controllers\Admin\Feedback\V1\Models\Feedback $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $photos
 * @property-read \App\Http\Controllers\Admin\Feedback\V1\Models\Feedback $top_parent
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media|null $video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereCreateableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereCreateableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereTopParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback whereVideoMediaId($value)
 * @mixin \Eloquent
 */
class Feedback extends Model
{
    protected $fillable = [
        'title', 'content', 'createable_type', 'createable_id', 'parent_id', 'top_parent_id', 'status', 'video_media_id'
    ];

    public function createable()
    {
        return $this->morphTo('createable');
    }

    public function top_parent()
    {
        return $this->belongsTo(Feedback::class, 'top_parent_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Feedback::class, 'parent_id', 'id');
    }

    public function childrenFeedback()
    {
        return $this->hasMany(Feedback::class, 'top_parent_id', 'id');
    }

    public function video()
    {
        return $this->belongsTo(Media::class, 'video_media_id', 'id');
    }

    public function photos()
    {
        return $this->morphToMany(Media::class, 'objectable', 'media_infos')
            ->wherePivot('type', "feedback");
    }


    const NO_STATUS = 0;
    const STATUS_WAITING_DEAL = 1;
    const STATUE_DEALT = 2;

    public static $statusAttributeMapping = [
        self::NO_STATUS => '无状态',
        self::STATUS_WAITING_DEAL => '待处理',
        self::STATUE_DEALT => '已处理',
    ];


}
