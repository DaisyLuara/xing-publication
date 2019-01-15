<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Ad\V1\Models\AdTrade;
use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use App\Http\Controllers\Admin\Ad\V1\Models\Advertiser;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdTradeTransformer;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdvertisementTransformer;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdvertiserTransformer;
use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Attribute\V1\Transformer\AttributeTransformer;
use App\Http\Controllers\Admin\Common\V1\Transformer\TeamProjectTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractReceiveDateTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceKindTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Models\Customer;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\GoodsServiceTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceCompanyTransformer;
use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentPayeeTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Area;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Point\V1\Models\Scene;
use App\Http\Controllers\Admin\Point\V1\Transformer\AreaTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\SceneTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplTransformer;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamRate;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamRateTransformer;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Admin\User\V1\Transformer\ArUserTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use DB;

class QueryController extends Controller
{
    public function areaQuery(Request $request, Area $area)
    {
        $query = $area->query();

        $user = $this->user();
        $arUserId = getArUserID($user, $request);

        //根据角色筛选
        if ($arUserId) {
            $query->whereHas('markets', function ($query) use ($arUserId) {
                $query->whereHas('points', function ($query) use ($arUserId) {
                    $query->where('bd_uid', '=', $arUserId);
                });
            });
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $areas = $query->where('areaid', '>', 0)->get();

        return $this->response->collection($areas, new AreaTransformer());
    }

    public function marketQuery(Request $request, Market $market)
    {
        $query = $market->query();
        $markets = collect();

        $user = $this->user();
        $arUserId = getArUserID($user, $request);

        //根据角色筛选
        if ($arUserId) {
            $query->whereHas('points', function ($query) use ($arUserId) {
                $query->where('bd_uid', '=', $arUserId);
            });
        }

        if (!$request->name && !$request->area_id) {
            return $this->response->collection($markets, new AreaTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->area_id) {
            $query->where('areaid', '=', $request->area_id);
        }

        $markets = $query->where('marketid', '>', 0)->get();

        return $this->response->collection($markets, new MarketTransformer());
    }

    public function pointQuery(Request $request, Point $point)
    {
        $query = $point->query();
        $points = collect();
        if (!$request->name && !$request->market_id) {
            return $this->response->collection($points, new AreaTransformer());
        }
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->market_id) {
            $query->where('marketid', '=', $request->market_id);
        }

        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        if ($arUserId) {
            $query->where('bd_uid', '=', $arUserId);
        }

        $points = $query->get();

        return $this->response->collection($points, new PointTransformer());
    }

    /**
     * 节目远程搜索
     * @param Request $request
     * @param Project $project
     * @return \Dingo\Api\Http\Response
     */
    public function projectQuery(Request $request, Project $project)
    {
        $user = $this->user();
        $arUserId = getArUserID($user, $request);
        $query = $project->query();
        if ($arUserId) {
            $query->whereHas('points', function ($q) use ($arUserId) {
                $q->where('bd_uid', '=', $arUserId);
            });
        }

        if ($request->alias) {
            $query->where('versionname', '=', $request->alias);
        }
        $project = $query->where('name', 'like', "%{$request->name}%")->get();
        return $this->response->collection($project, new ProjectTransformer());
    }

    /**
     * 团队节目远程搜索
     * @param Request $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function teamProjectQuery(Request $request, TeamProject $teamProject)
    {
        $query = $teamProject->query();

        if ($request->belong) {
            $query->where('belong', '=', $request->belong);
        }

        if ($request->has('copyright_attribute')) {
            $query->where('copyright_attribute', '=', $request->copyright_attribute ?? 0);
        }

        $team_project = $query->where('project_name', 'like', "%{$request->project_name}%")->get();
        return $this->response->collection($team_project, new TeamProjectTransformer());
    }


    public function launchTplQuery(Request $request, ProjectLaunchTpl $projectLaunchTpl)
    {
        $query = $projectLaunchTpl->query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $templates = $query->where('oid', '=', 0)->get();

        return $this->response->collection($templates, new ProjectLaunchTplTransformer());

    }


    public function adTradeQuery(Request $request, AdTrade $adTrade)
    {
        $query = $adTrade->query();
        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $adTrade = $query->get();
        return $this->response->collection($adTrade, new AdTradeTransformer());
    }

    public function advertiserQuery(Request $request, Advertiser $advertiser)
    {
        $query = $advertiser->query();
        $advertiser = collect();
        if (!$request->name && !$request->ad_trade_id) {
            return $this->response->collection($advertiser, new AdvertiserTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->ad_trade_id) {
            $query->where('atid', '=', $request->ad_trade_id);
        }
        $advertiser = $query->get();
        return $this->response->collection($advertiser, new AdvertiserTransformer());
    }

    public function advertisementQuery(Request $request, Advertisement $advertisement)
    {
        $query = $advertisement->query();
        $advertisement = collect();
        if (!$request->advertiser_id && !$request->name) {
            return $this->response->collection($advertisement, new AdvertisementTransformer());
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->advertiser_id) {
            $query->where('atiid', '=', $request->advertiser_id);
        }
        $advertisement = $query->get();
        return $this->response->collection($advertisement, new AdvertisementTransformer());
    }

    public function sceneQueryIndex(Scene $scene)
    {
        return $this->response->collection($scene->where('sid', '>', 0)->get(), new SceneTransformer());
    }

    public function arUserQueryIndex(Request $request, ArUser $arUser)
    {
        if ($this->user()->isUser()) {
            $query = $arUser->query();
            $arUser = $query->where('uid', '=', $this->user()->ar_user_id)->get();
            return $this->response->collection($arUser, new ArUserTransformer());
        } else {
            $query = $arUser->query();
            $arUsers = collect();
            if ($request->name) {
                $arUsers = $query->where('realname', 'like', '%' . $request->name . '%')->get();
            }
            return $this->response->collection($arUsers, new ArUserTransformer());
        }
    }

    public function couponBatchQuery(CouponBatch $couponBatch, Request $request)
    {
        $query = $couponBatch->query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->company_id) {
            $query->where('company_id', '=', $request->company_id);
        }

        $couponBatches = $query->get();
        return $this->response->collection($couponBatches, new CouponBatchTransformer());
    }

    public function companyQuery(Company $company, Request $request)
    {
        $query = $company->query();

        /** @var  $loginUser \App\Models\User */
        $loginUser = $this->user;

        if (!$loginUser->isAdmin() && !$loginUser->hasRole('legal-affairs') && !$loginUser->hasRole('legal-affairs-manager')) {
            $query->where('user_id', '=', $loginUser->id);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%')->get();
        }

        $companies = $query->get();
        return $this->response->collection($companies, new CompanyTransformer());
    }

    public function customerQuery(Request $request)
    {
        $builder = Customer::query();
        $loginUser = $this->user;
        if (!$loginUser->isAdmin() && !$loginUser->hasRole('legal-affairs') && !$loginUser->hasRole('legal-affairs-manager')) {
            $builder->whereHas('company', function ($builder) use ($loginUser) {
                $builder->where('user_id', '=', $loginUser->id);
            });
        }

        $customers = $builder->where('company_id', $request->company_id)->get();
        return $this->response->collection($customers, new CustomerTransformer());
    }

    public function policyQuery(Policy $policy, Request $request)
    {
        $query = $policy->query();

        $loginUser = $this->user;

        if (!$loginUser->isAdmin()) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%')->get();
        }

        $policies = $query->get();
        return $this->response->collection($policies, new PolicyTransformer());
    }

    public function contractQuery(Contract $contract, Request $request)
    {
        $query = $contract->query();
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->contract_number) {
            $query->where('contract_number', 'like', '%' . $request->contract_number . '%');
        }

        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('applicant', '=', $user->id);
        }
        $contracts = $query->where('status', '3')->get();

        return $this->response->collection($contracts, new ContractTransformer());
    }

    public function invoiceKindQuery(InvoiceKind $invoiceKind, Request $request)
    {
        $query = $invoiceKind->query();
        $invoiceKind = $query->get();
        return $this->response()->collection($invoiceKind, new InvoiceKindTransformer());
    }

    public function goodsServiceQuery(GoodsService $goodsService, Request $request)
    {
        $query = $goodsService->query();
        $goodsService = $query->where('invoice_kind_id', $request->invoice_kind_id)->get();
        return $this->response->collection($goodsService, new GoodsServiceTransformer());
    }

    public function warehouseQuery(Request $request)
    {
        return DB::table('erp_warehouses')->get();
    }

    public function bdManagerQuery(Request $request)
    {
        $role = Role::findByName('bd-manager');
        $users = $role->users()->get();
        return $this->response->collection($users, new UserTransformer());
    }

    public function legalManagerQuery(Request $request)
    {
        $role = Role::findByName('legal-affairs-manager');
        $users = $role->users()->get();
        return $this->response->collection($users, new UserTransformer());
    }

    public function invoiceCompanyQuery(Request $request, InvoiceCompany $invoiceCompany)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query = $invoiceCompany->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('user_id', $user->id);
        }
        $invoiceCompany = $query->get();
        return $this->response()->collection($invoiceCompany, new InvoiceCompanyTransformer());
    }

    public function paymentPayeeQuery(Request $request, PaymentPayee $paymentPayee)
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query = $paymentPayee->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('user_id', $user->id);
        }
        $paymentPayee = $query->get();
        return $this->response()->collection($paymentPayee, new PaymentPayeeTransformer());
    }

    public function receiveDateQuery(Request $request, ContractReceiveDate $contractReceiveDate)
    {
        $query = $contractReceiveDate->query();
        $contractReceiveDate = $query->where('contract_id', $request->id)->whereRaw("invoice_receipt_id is null")->get();
        return $this->response()->collection($contractReceiveDate, new ContractReceiveDateTransformer());
    }

    public function userQuery(Request $request, User $user)
    {
        $query = $user->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        $user = $query->get();
        return $this->response()->collection($user, new UserTransformer());
    }

    public function teamRateQuery(TeamRate $teamRate)
    {
        $query = $teamRate->query();
        $teamRate = $query->get();
        return $this->response()->collection($teamRate, new TeamRateTransformer());
    }

    public function attributeQuery(Request $request)
    {
        /** @var \Baum\Node $node */
        $node = Attribute::query()->where('name', '业态')->first();
        $attribute = $node->getDescendants();
        return $this->response()->collection($attribute, new AttributeTransformer());
    }

    public function erpAttributeQuery(Request $request)
    {
        return DB::table('erp_attributes')->get();
    }

    public function erpSupplierQuery(Company $company, Request $request)
    {
        $query = $company->query();
        $companies = $query->where('category', '=', 1)->get();
        return $this->response->collection($companies, new CompanyTransformer());
    }

    public function erpSkuQuery(Company $company, Request $request)
    {
        return DB::table('erp_products')->select('id','sku')->get();
    }

    public function erpLocationQuery(Company $company, Request $request)
    {
        return DB::table('erp_locations')->select('id','name')->get();
    }

    public function bdAndBdManagerQuery(Request $request)
    {
        $bdRole = Role::findByName('user');
        $bds = $bdRole->users()->get();

        $bdManagerRole = Role::findByName('bd-manager');
        $bdManagers = $bdManagerRole->users()->get();

        $merged = $bds->merge($bdManagers);

        return $this->response->collection($merged, new UserTransformer());
    }

}
