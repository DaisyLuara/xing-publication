<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/8
 * Time: 上午10:31
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Api;


use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use App\Http\Controllers\Admin\Invoice\V1\Request\InvoiceCompanyRequest;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceCompanyTransformer;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;

class InvoiceCompanyController extends Controller
{
    public function show(InvoiceCompany $invoiceCompany): Response
    {
        return $this->response()->item($invoiceCompany, new InvoiceCompanyTransformer());
    }

    public function index(Request $request, InvoiceCompany $invoiceCompany): Response
    {
        $query = $invoiceCompany->query();

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        /** @var  $user \App\Models\User */
        $user = $this->user();
        if ($user->hasRole('user|bd-manager')) {
            $query->where('owner', $user->id);
        }
        $invoiceCompany = $query->orderByDesc('created_at')->paginate(10);

        return $this->response()->paginator($invoiceCompany, new InvoiceCompanyTransformer());
    }

    public function store(InvoiceCompanyRequest $request, InvoiceCompany $invoiceCompany): Response
    {
        /** @var  $user  \App\Models\User */
        $user = $this->user();
        if (!$user->hasRole('user|bd-manager|legal-affairs|legal-affairs-manager')) {
            abort(403, '无操作权限');
        }
        $invoiceCompany->fill(array_merge($request->all(), ['user_id' => $user->id, 'owner' => $user->id]))->save();
        return $this->response()->noContent();
    }

    public function update(InvoiceCompanyRequest $request, InvoiceCompany $invoiceCompany): Response
    {
        $user = $this->user();
        if ($user->id !== $invoiceCompany->user_id) {
            abort(403, '无操作权限');
        }
        $invoiceCompany->update($request->all());
        return $this->response()->noContent();
    }


    public function export(Request $request)
    {
        return excelExportByType($request, 'invoice_company');
    }
}