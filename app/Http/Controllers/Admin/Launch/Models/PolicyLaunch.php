<?php

namespace App\Http\Controllers\Admin\Launch\V1\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch
 *
 * @property int $id
 * @property int $company_id 公司ID
 * @property int $policy_id 策略ID
 * @property int $project_id 节目ID
 * @property string $belong 节目版本名称
 * @property int $oid 点位ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Http\Controllers\Admin\Company\V1\Models\Company $company
 * @property-read \App\Http\Controllers\Admin\Point\V1\Models\Point $point
 * @property-read \App\Http\Controllers\Admin\Coupon\V1\Models\Policy $policy
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereBelong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereCompanyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereOid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch wherePolicyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Launch\V1\Models\PolicyLaunch whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PolicyLaunch extends Model
{
    protected $fillable = [
        'company_id',
        'policy_id',
        'project_id',
        'oid',
        'belong',
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

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
