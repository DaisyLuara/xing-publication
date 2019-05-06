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
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublicationMedia extends Model
{
    protected $fillable = ['media_id', 'creator'];

    public $timestamps = false;

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }
}