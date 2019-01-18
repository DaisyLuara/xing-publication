<?php

namespace App\Http\Controllers\Admin\Warehouse\V1\Models;

use App\Models\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attribute extends Model
{
    protected $table = 'erp_attributes';
    use SoftDeletes;

    public $fillable = [
        'name',
        'display_name'
    ];

    public function attributeValues()
    {
        return $this->hasMany(AttributeValue::class,'attribute_id','id');
    }
}