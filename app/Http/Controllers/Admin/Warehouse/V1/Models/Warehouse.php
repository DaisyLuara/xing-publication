<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse
 *
 * @property int $id 仓库ID
 * @property string|null $name 仓库名称
 * @property string $address 仓库地址
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Http\Controllers\Admin\Warehouse\V1\Models\Warehouse withoutTrashed()
 * @mixin \Eloquent
 */
class Warehouse extends Model
{
    protected $table = 'erp_warehouses';

    use SoftDeletes;

    public $fillable = [
        'name',
        'address'
    ];
}
