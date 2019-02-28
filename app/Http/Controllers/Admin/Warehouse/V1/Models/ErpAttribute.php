<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErpAttribute extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'display_name'
    ];

    public function attributeValues()
    {
        return $this->hasMany(ErpAttributeValue::class, 'attribute_id', 'id');
    }
}
