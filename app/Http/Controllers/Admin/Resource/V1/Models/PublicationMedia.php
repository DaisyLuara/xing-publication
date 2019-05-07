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

/**
 * App\Http\Controllers\Admin\Resource\V1\Models\PublicationMedia
 *
 * @property int $id
 * @property int $group_id
 * @property int $media_id
 * @property int $creator
 * @property-read \App\Http\Controllers\Admin\Media\V1\Models\Media $media
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMedia whereMediaId($value)
 * @mixin \Eloquent
 */
class PublicationMedia extends Model
{
    protected $fillable = ['group_id','media_id', 'creator'];

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