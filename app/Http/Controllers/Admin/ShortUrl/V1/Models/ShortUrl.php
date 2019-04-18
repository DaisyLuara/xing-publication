<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl
 *
 * @property int $id
 * @property string $target_url
 * @property string|null $short_url
 * @property int $source 0 内部调用生成 1 用户定义
 * @property int $url_type 0 内部链接 1 跳转外部
 * @property int $tenant_id
 * @property int|null $landing_record_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $description
 * @property string|null $channel 渠道
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\ShortUrl\V1\Models\PeopleViewRecords[] $shortUrlRecords
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereLandingRecordId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereShortUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereTargetUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereTenantId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrl whereUrlType($value)
 * @mixin \Eloquent
 */
class ShortUrl extends Model
{
    public $fillable = [
        'target_url',
        'short_url',
        'source',
        'url_type',
        'scan_count',
        'tenant_id',
        'landing_record_id',
        'description',
        'channel'
    ];

    public function shortUrlRecords()
    {
        return $this->hasMany(PeopleViewRecords::class, 'short_url_id', 'id');
    }

}
