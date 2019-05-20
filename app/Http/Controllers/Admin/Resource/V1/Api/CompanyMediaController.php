<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/24
 * Time: 上午11:06
 */

namespace App\Http\Controllers\Admin\Resource\V1\Api;


use App\Http\Controllers\Admin\Resource\V1\Models\CompanyMedia;
use App\Http\Controllers\Admin\Resource\V1\Request\CompanyMediaRequest;
use App\Http\Controllers\Admin\Resource\V1\Transformer\CompanyMediaTransformer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyMediaController extends Controller
{
    public function index(Request $request, CompanyMedia $companyMedia)
    {
        $query = $companyMedia->query();

        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }
        $companyMedia = $query->paginate(10);
        return $this->response()->paginator($companyMedia, new CompanyMediaTransformer())->setStatusCode(200);
    }

    public function audit(CompanyMediaRequest $request, CompanyMedia $companyMedia)
    {
        /** @var User $user */
        $user = $this->user();
        $companyMedia->update(['status' => $request->get('status'), 'audit_user_id' => $user->id]);
        return $this->response()->noContent()->setStatusCode(200);
    }
}