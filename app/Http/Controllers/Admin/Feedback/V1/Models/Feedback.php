<?php

namespace App\Http\Controllers\Admin\Feedback\V1\Models;

use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Feedback\V1\Models\Feedback
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback[] $childrenFeedback
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $createable
 * @property-read \App\Http\Controllers\Admin\Feedback\V1\Models\Feedback $parent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Media\V1\Models\Media[] $photos
 * @property-read \App\Http\Controllers\Admin\Feedback\V1\Models\Feedback $top_parent
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media $video
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Feedback\V1\Models\Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
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
