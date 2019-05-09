<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/9
 * Time: 下午5:00
 */

namespace App\Http\Controllers\Admin\Resource\V1\Models;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyMediaGroup extends Model
{
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}