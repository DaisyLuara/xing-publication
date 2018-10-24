<?php

namespace App\Http\Controllers\Admin\Company\V1\Api;

use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Company\V1\Request\CompanyRequest;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCompaniesController extends Controller
{
    public function index(Request $request, Company $company)
    {
        $query = $company->query();
        $currentUser = $this->user();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($currentUser->isAdmin()) {
            $companies = $query->orderByDesc('id')->paginate(10);
        } else {
            $companies = $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('id', $currentUser->id);
            })->orderByDesc('id')->paginate(10);
        }

        return $this->response->paginator($companies, new CompanyTransformer());
    }

    public function show(Company $company)
    {
        $this->authorize('show', $company);

        return $this->response->item($company, new CompanyTransformer());
    }

    public function store(CompanyRequest $request, Company $company)
    {
        $company->fill($request->all());
        $company->user_id = $this->user()->id;
        $company->save();

        return $this->response->item($company, new CompanyTransformer())
            ->setStatusCode(201);

    }

    public function update(CompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->all());
        return $this->response->item($company, new CompanyTransformer());
    }
}
