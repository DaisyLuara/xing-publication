<?php

namespace App\Http\Controllers\Admin\ShortUrl\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords
 *
 * @property int $id
 * @property int $short_url_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $utm_source
 * @property string $utm_medium
 * @property string $utm_term
 * @property string $utm_campaign
 * @property string $utm_content
 * @property string $ua
 * @property string $ip
 * @property int $third_id
 * @property string $face_id
 * @property string $browser
 * @property string $device
 * @property string $platform
 * @property string $app
 * @property int $clientdate
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereApp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereFaceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereShortUrlId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereThirdId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUtmCampaign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUtmContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUtmMedium($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUtmSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords whereUtmTerm($value)
 * @mixin \Eloquent
 */
class ShortUrlRecords extends Model
{
    public $fillable = [
        'short_url_id',
        'utm_source',
        'utm_medium',
        'utm_term',
        'utm_campaign',
        'utm_content',
        'ua',
        'ip',
        'face_id',
        'browser',
        'platform',
        'device',
        'app',
        'third_id',
        'clientdate'
    ];
}
