<?php

namespace App\Http\Controllers\Admin\Point\V1\Models;

use App\Models\Model;


class PointShare extends Model
{
    protected $connection = 'ar';
    public $table = 'avr_official_share';
    protected $primaryKey = 'oid';

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }
}
