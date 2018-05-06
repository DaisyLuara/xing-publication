<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Company;
use App\Transformers\CompanyTransformer;
use App\Http\Requests\Api\V1\CompanyRequest;

class AdminCompaniesController extends Controller
{
    public function index(Company $company)
    {
        $query = $company->query();
        $currentUser = $this->user();
        if ($currentUser->isAdmin()) {
            $companies = $query->paginate(10);
        } else {
            $companies = $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('id', $currentUser->id);
            })->paginate(10);
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
