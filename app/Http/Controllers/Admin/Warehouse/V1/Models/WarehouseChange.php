<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseChange extends Model
{
    use SoftDeletes;

    protected $table = 'erp_warehouse_changes';

    public $fillable = [
        'product_id',
        'out_location',
        'in_location',
        'num',
        'remark'
    ];

    public function product(){
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function outLocation(){
        return $this->belongsTo(Location::class,'out_location','id');
    }

    public function inLocation(){
        return $this->belongsTo(Location::class, 'in_location', 'id');
    }

}
