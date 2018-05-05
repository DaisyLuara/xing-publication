<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\CustomerRequest;
use App\Transformers\CustomerTransformer;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function index(Customer $customer)
    {
        $query = $customer->query();
        $currentUser = $this->user();
        if ($currentUser->isAdmin()) {
            $customers = $query->paginate(10);
        } else {
            $customers = $query->whereHas('user', function ($q) use ($currentUser) {
                $q->where('id', $currentUser->id);
            })->paginate(10);
        }

        return $this->response->paginator($customers, new CustomerTransformer());
    }

    public function show()
    {

    }

    public function store(CustomerRequest $request, Customer $customer)
    {
        $customer->fill($request->all());
        $customer->user_id = $this->user()->id;
        $customer->save();

        return $this->response->item($customer, new CustomerTransformer())
            ->setStatusCode(201);

    }

    public function update()
    {

    }

}
