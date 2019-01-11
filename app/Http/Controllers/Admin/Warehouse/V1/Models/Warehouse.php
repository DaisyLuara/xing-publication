<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    protected $table = 'erp_warehouses';

    use SoftDeletes;

    public $fillable = [
        'name',
        'address'
    ];
}
