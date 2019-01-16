<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    protected $table = 'erp_locations';
    use SoftDeletes;

    public $fillable = [
        'name',
        'warehouse_id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
