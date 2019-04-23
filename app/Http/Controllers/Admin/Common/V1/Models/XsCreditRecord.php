<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Common\V1\Models\XsCreditRecord
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\XsCreditRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\XsCreditRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\XsCreditRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @mixin \Eloquent
 */
class XsCreditRecord extends Model
{
    protected $connection = 'ar';
    protected $table = 'xs_credit_records';
    protected $fillable = ['uid', 'num', 'key'];

}
