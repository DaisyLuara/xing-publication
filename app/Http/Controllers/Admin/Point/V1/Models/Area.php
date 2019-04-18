<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Point\V1\Models\Area
 *
 * @property int $areaid
 * @property int $pid 产品ID
 * @property int $cid 公司ID
 * @property string $name 名称
 * @property string $icon 图标
 * @property string $area_key 密钥
 * @property string $date
 * @property int $clientdate 时间
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Point\V1\Models\Market[] $markets
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereAreaKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereAreaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereCid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Point\V1\Models\Area wherePid($value)
 * @mixin \Eloquent
 */
class Area extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_area';
    protected $primaryKey = 'areaid';

    public function markets()
    {
        return $this->hasMany(Market::class, 'areaid', 'areaid');
    }
}
