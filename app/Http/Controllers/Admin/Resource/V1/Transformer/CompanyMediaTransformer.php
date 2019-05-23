<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/24
 * Time: 上午11:25
 */

namespace App\Http\Controllers\Admin\Resource\V1\Transformer;


use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Admin\Resource\V1\Models\CompanyMedia;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class CompanyMediaTransformer extends TransformerAbstract
{
    public function transform(CompanyMedia $companyMedia): array
    {
        /** @var Company $company */
        $company = $companyMedia->group->company;
        $media = $companyMedia->media;
        return [
            'id' => $companyMedia->id,
            'name' => $media->name,
            'url' => $media->url,
            'status' => $companyMedia->status,
            'company_name' => $company->name,
            'audit_user_name' => $companyMedia->audit_user_id ? $companyMedia->auditor->name : null,
            'type' => $companyMedia->group->type,
            'created_at' => $media->created_at->toDateTimeString(),
            'updated_at' => $media->updated_at->toDateTimeString(),
        ];
    }
}