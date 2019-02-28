<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpAttributeValue extends Model
{
    use SoftDeletes;

    public $fillable = [
        'attribute_id',
        'value'
    ];

    public function attribute()
    {
        return $this->belongsTo(ErpAttribute::class, 'attribute_id', 'id');
    }
}
