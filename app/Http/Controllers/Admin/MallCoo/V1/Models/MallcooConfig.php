<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig
 *
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Market $market
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\MallCoo\V1\Models\MallcooConfig query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @mixin \Eloquent
 */
class MallcooConfig extends Model
{
    protected $table = 'mallcoo_config';

    protected $fillable = [];

    public function market()
    {
        return $this->belongsTo(Market::class, 'marketid', 'marketid');
    }


}
