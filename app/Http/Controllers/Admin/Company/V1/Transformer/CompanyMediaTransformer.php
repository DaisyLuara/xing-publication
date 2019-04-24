<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/24
 * Time: 上午11:25
 */

namespace App\Http\Controllers\Admin\Company\V1\Transformer;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CompanyMediaTransformer extends TransformerAbstract
{
    public function transform(Media $media): array
    {
        /** @var Company $company */
        $company = $media->company()->first();
        $auditUser = User::find($company->pivot->audit_user_id);
        return [
            'id' => $media->id,
            'name' => $media->name,
            'url' => $media->url,
            'status' => $company->pivot->status,
            'company_name' => $company->name,
            'audit_user_id' => $company->pivot->audit_user_id,
            'audit_user_name' => $auditUser ? $auditUser->name : null,
            'created_at' => $media->created_at->toDateTimeString(),
            'updated_at' => $media->updated_at->toDateTimeString(),
        ];
    }
}