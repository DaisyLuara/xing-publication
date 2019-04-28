<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/24
 * Time: 上午11:06
 */

namespace App\Http\Controllers\Admin\Auditing\V1\Api;


use App\Http\Controllers\Admin\Auditing\V1\Request\CompanyMediaRequest;
use App\Http\Controllers\Admin\Auditing\V1\Transformer\CompanyMediaTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Media\V1\Models\Media;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyMediaController extends Controller
{
    public function index(Request $request, Media $media)
    {
        $query = $media->query();

        $media = $query->whereHas('company', static function ($q) use ($request) {
            if ($request->has('status')) {
                $q->where('status', $request->get('status'));
            }
            if ($request->has('company_name')) {
                $q->where('name', 'like', '%' . $request->get('company_name') . '%');
            }
        })->orderBy('created_at')->paginate(10);
        return $this->response()->paginator($media, new CompanyMediaTransformer())->setStatusCode(200);
    }

    public function audit(CompanyMediaRequest $request, Media $media)
    {
        /** @var User $user */
        $user = $this->user();
        /** @var Company $company */
        $company = $media->company()->first();
        $media->company()->updateExistingPivot($company->id, ['status' => $request->get('status'), 'audit_user_id' => $user->id]);
        return $this->response()->noContent()->setStatusCode(200);
    }

}