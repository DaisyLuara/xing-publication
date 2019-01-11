<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeValue extends Model
{
    protected $table = 'erp_attribute_values';
    use SoftDeletes;

    public $fillable = [
        'attribute_id',
        'value'
    ];
}
