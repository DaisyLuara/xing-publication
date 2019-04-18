<?php

namespace App\Http\Controllers\Admin\Company\V1\Models;

use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Company\V1\Models\CompanyProject
 *
 * @property int $id
 * @property int $company_id
 * @property int $project_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Company\V1\Models\CompanyProject whereUserId($value)
 * @mixin \Eloquent
 */
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
