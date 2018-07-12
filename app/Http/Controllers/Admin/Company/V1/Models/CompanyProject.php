<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Models\Model;


class CompanyProject extends Model
{

    protected $connection = 'ar';
    public $table = 'xs_company_project';
    public $timestamps = false;

    public $fillable = [
        'company_id',
        'user_id',
        'project_id',
    ];
}
