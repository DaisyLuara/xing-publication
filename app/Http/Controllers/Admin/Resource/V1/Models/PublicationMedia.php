<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/5
 * Time: 下午5:10
 */

namespace App\Http\Controllers\Admin\Resource\V1\Models;


use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\Model;

class PublicationMedia extends Model
{
    protected $fillable = ['media_id'];

    public $timestamps = false;

    public function media(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }
}