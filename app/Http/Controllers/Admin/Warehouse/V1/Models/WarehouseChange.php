<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseChange extends Model
{
    use SoftDeletes;

    protected $table = 'erp_warehouse_changes';

    public $fillable = [
        'sku',
        'out_location',
        'in_location',
        'num',
        'remark'
    ];
}
