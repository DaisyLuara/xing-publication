<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Models\ArModel;
use App\Models\Customer;

class ProjectAuth extends ArModel
{
    public $table = 'admin_per_pid';

    public $fillable = [
        'id',
        'z',
        'pid',
        'date',
        'clientdate',
    ];

    public function customer()
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'z', 'z');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'pid', 'id');
    }

}
