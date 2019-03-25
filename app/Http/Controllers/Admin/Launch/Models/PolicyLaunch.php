<?php

namespace App\Http\Controllers\Admin\Launch\V1\Models;

use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

class PolicyLaunch extends Model
{
    protected $fillable = [
        'company_id',
        'policy_id',
        'project_id',
        'oid',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }

    public function point()
    {
        return $this->belongsTo(Point::class, 'oid', 'oid');
    }

    public function policy()
    {
        return $this->belongsTo(Policy::class, 'policy_id', 'id');
    }
}
