<?php

namespace App\Http\Controllers\Admin\Common\V1\Api;

use App\Http\Controllers\Admin\Activity\V1\Models\PlayingType;
use App\Http\Controllers\Admin\Activity\V1\Transformer\PlayingTypeTransformer;
use App\Http\Controllers\Admin\Ad\V1\Models\AdTrade;
use App\Http\Controllers\Admin\Ad\V1\Models\Advertisement;
use App\Http\Controllers\Admin\Ad\V1\Models\AdPlan;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdTradeTransformer;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdvertisementTransformer;
use App\Http\Controllers\Admin\Ad\V1\Transformer\AdPlanTransformer;
use App\Http\Controllers\Admin\Attribute\V1\Models\Attribute;
use App\Http\Controllers\Admin\Attribute\V1\Transformer\AttributeTransformer;
use App\Http\Controllers\Admin\Common\V1\Transformer\DemandApplicationTransformer;
use App\Http\Controllers\Admin\Common\V1\Transformer\TeamProjectTransformer;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Company\V1\Transformer\CompanyTransformer;
use App\Http\Controllers\Admin\Company\V1\Transformer\CustomerTransformer;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractCostKind;
use App\Http\Controllers\Admin\Contract\V1\Models\ContractReceiveDate;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractCostKindTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractReceiveDateTransformer;
use App\Http\Controllers\Admin\Contract\V1\Transformer\ContractTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Models\CouponBatch;
use App\Http\Controllers\Admin\Coupon\V1\Models\Policy;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\CouponBatchTransformer;
use App\Http\Controllers\Admin\Coupon\V1\Transformer\PolicyTransformer;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Invoice\V1\Models\GoodsService;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\GoodsServiceTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceCompanyTransformer;
use App\Http\Controllers\Admin\Invoice\V1\Transformer\InvoiceKindTransformer;
use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use App\Http\Controllers\Admin\Payment\V1\Transformer\PaymentPayeeTransformer;
use App\Http\Controllers\Admin\Point\V1\Models\Area;
use App\Http\Controllers\Admin\Point\V1\Models\Market;
use App\Http\Controllers\Admin\Point\V1\Models\MarketConfig;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\Point\V1\Models\Scene;
use App\Http\Controllers\Admin\Point\V1\Models\Store;
use App\Http\Controllers\Admin\Point\V1\Transformer\AreaTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\MarketTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\PointTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\SceneTransformer;
use App\Http\Controllers\Admin\Point\V1\Transformer\StoreTransformer;
use App\Http\Controllers\Admin\Privilege\V1\Models\Permission;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use App\Http\Controllers\Admin\Privilege\V1\Transformer\RoleTransformer;
use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Project\V1\Models\ProjectLaunchTpl;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectLaunchTplTransformer;
use App\Http\Controllers\Admin\Project\V1\Transformer\ProjectTransformer;
use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Http\Controllers\Admin\Skin\V1\Transformer\SkinTransformer;
use App\Http\Controllers\Admin\Team\V1\Models\TeamProject;
use App\Http\Controllers\Admin\Team\V1\Models\TeamRate;
use App\Http\Controllers\Admin\Team\V1\Transformer\TeamRateTransformer;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Admin\User\V1\Transformer\ArUserTransformer;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\Warehouse\V1\Models\ErpAttribute;
use App\Http\Controllers\Admin\Warehouse\V1\Transformer\ErpAttributeTransformer;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use DB;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    public function areaQuery(Request $request, Area $area): Response
    {
        $query = $area->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $areas = $query->where('areaid', '>', 0)->get();

        return $this->response()->collection($areas, new AreaTransformer());
    }

    public function marketQuery(Request $request, Market $market): Response
    {
        $query = $market->query();
        $markets = collect();

        if (!$request->has('name') && !$request->has('area_id')) {
            return $this->response()->collection($markets, new AreaTransformer());
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('area_id')) {
            $query->where('areaid', '=', $request->get('area_id'));
        }

        $markets = $query->where('marketid', '>', 0)->get();

        return $this->response()->collection($markets, new MarketTransformer());
    }

    public function pointQuery(Request $request, Point $point): Response
    {
        $query = $point->query();
        $points = collect();
        if (!$request->has('name') && !$request->has('market_id')) {
            return $this->response()->collection($points, new AreaTransformer());
        }
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('market_id')) {
            $query->where('marketid', '=', $request->get('market_id'));
        }

        $user = $this->user();
        $arUserZ = getArUserZ($user, $request);
        if ($arUserZ) {
            $query->where('bd_z', '=', $arUserZ);
        }

        $points = $query->get();

        return $this->response()->collection($points, new PointTransformer());
    }

    /**
     * 节目远程搜索
     * @param Request $request
     * @param Project $project
     * @return \Dingo\Api\Http\Response
     */
    public function projectQuery(Request $request, Project $project): Response
    {
        $user = $this->user();
        $arUserZ = getArUserZ($user, $request);
        $query = $project->query();
        if ($arUserZ) {
            $query->whereHas('points', static function ($q) use ($arUserZ) {
                $q->where('bd_z', '=', $arUserZ);
            });
        }

        if ($request->has('alias')) {
            $query->where('versionname', '=', $request->get('alias'));
        }
        $project = $query->where('name', 'like', "%{$request->get('name')}%")->get();
        return $this->response()->collection($project, new ProjectTransformer());
    }

    /**
     * 团队节目远程搜索
     * @param Request $request
     * @param TeamProject $teamProject
     * @return \Dingo\Api\Http\Response
     */
    public function teamProjectQuery(Request $request, TeamProject $teamProject): Response
    {
        $query = $teamProject->query();

        if ($request->has('belong')) {
            $query->where('belong', '=', $request->get('belong'));
        }

        if ($request->has('copyright_attribute')) {
            $query->where('copyright_attribute', '=', $request->copyright_attribute ?? 0);
        }

        $team_project = $query->where('project_name', 'like', "%{$request->get('project_name')}%")->get();
        return $this->response()->collection($team_project, new TeamProjectTransformer());
    }


    public function launchTplQuery(Request $request, ProjectLaunchTpl $projectLaunchTpl): Response
    {
        $query = $projectLaunchTpl->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        $templates = $query->where('oid', '=', 0)->get();

        return $this->response()->collection($templates, new ProjectLaunchTplTransformer());

    }


    /**
     * 广告行业
     * @param Request $request
     * @param AdTrade $adTrade
     * @return \Dingo\Api\Http\Response
     */
    public function adTradeQuery(Request $request, AdTrade $adTrade): Response
    {
        $query = $adTrade->query();
        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        $adTrades = $query->get();
        return $this->response()->collection($adTrades, new AdTradeTransformer());
    }

    /**
     * 广告方案
     * @param Request $request
     * @param AdPlan $adPlan
     * @return \Dingo\Api\Http\Response
     */
    public function adPlanQuery(Request $request, AdPlan $adPlan): Response
    {
        $query = $adPlan->query();

        if (!$request->get('name') && !$request->get('ad_trade_id') && !$request->get('type')) {
            return $this->response()->collection(collect(), new AdPlanTransformer());
        }

        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->get('ad_trade_id')) {
            $query->where('atid', '=', $request->get('ad_trade_id'));
        }

        if ($request->get('type')) {
            $query->where('type', '=', $request->get('type'));
        }

        $adPlans = $query->get();
        return $this->response()->collection($adPlans, new AdPlanTransformer());
    }

    /**
     * 搜索广告素材
     * @param Request $request
     * @param Advertisement $advertisement
     * @return Response
     */
    public function advertisementQuery(Request $request, Advertisement $advertisement): Response
    {
        $query = $advertisement->query();

        if (!$request->get('name') && !$request->get('type') && !$request->get('atid')) {
            return $this->response()->collection(collect(), new AdvertisementTransformer());
        }

        if ($request->get('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->get('type')) {
            $query->where('type', '=', $request->get('type'));
        }

        if ($request->get('atid')) {
            $query->where('atid', '=', $request->get('atid'));
        }

        $advertisements = $query->get();
        return $this->response()->collection($advertisements, new AdvertisementTransformer());
    }

    public function sceneQueryIndex(Scene $scene): Response
    {
        return $this->response()->collection($scene->where('sid', '>', 0)->get(), new SceneTransformer());
    }

    public function arUserQueryIndex(Request $request, ArUser $arUser): Response
    {

        $query = $arUser->query();
        $arUsers = collect();
        if ($request->has('name')) {
            $arUsers = $query->where('realname', 'like', '%' . $request->get('name') . '%')->get();
        }
        return $this->response()->collection($arUsers, new ArUserTransformer());
    }

    public function couponBatchQuery(CouponBatch $couponBatch, Request $request): Response
    {
        $query = $couponBatch->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($request->has('company_id')) {
            $query->where('company_id', '=', $request->get('company_id'));
        }

        $couponBatches = $query->get();
        return $this->response()->collection($couponBatches, new CouponBatchTransformer());
    }

    public function companyQuery(Company $company, Request $request): Response
    {
        $query = $company->query();

        /** @var  $loginUser \App\Models\User */
        $loginUser = $this->user();

        if (!($loginUser->isAdmin() || $loginUser->hasRole('legal-affairs|legal-affairs-manager'))) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%')->get();
        }

        $companies = $query->get();
        return $this->response()->collection($companies, new CompanyTransformer());
    }

    public function customerQuery(Request $request): Response
    {
        $builder = Customer::query();
        $loginUser = $this->user();
        if (!($loginUser->isAdmin() || $loginUser->hasRole('legal-affairs|legal-affairs-manager'))) {
            $builder->whereHas('company', static function ($builder) use ($loginUser) {
                $builder->where('bd_user_id', '=', $loginUser->id);
            });
        }

        $customers = $builder->where('company_id', $request->get('company_id'))->get();
        return $this->response()->collection($customers, new CustomerTransformer());
    }

    public function policyQuery(Policy $policy, Request $request): Response
    {
        $query = $policy->query();

        $loginUser = $this->user();

        if (!$loginUser->isAdmin()) {
            $query->where('bd_user_id', '=', $loginUser->id);
        }

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%')->get();
        }

        $policies = $query->get();
        return $this->response()->collection($policies, new PolicyTransformer());
    }

    public function contractQuery(Contract $contract, Request $request): Response
    {
        $query = $contract->query();
        /** @var  $user \App\Models\User */
        $user = $this->user();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($request->has('contract_number')) {
            $query->where('contract_number', 'like', '%' . $request->get('contract_number') . '%');
        }

        if ($request->has('company_id')) {
            $query->where('company_id', $request->get('company_id'));
        }

        //合同类型
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        //合同成本
        if ($request->has('cost') && $request->get('cost') === 0) {
            $query->doesntHave('contractCost');
        }

        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('owner', '=', $user->id);
        }
        $contracts = $query->where('status', '3')->get();

        return $this->response()->collection($contracts, new ContractTransformer());
    }

    public function invoiceKindQuery(InvoiceKind $invoiceKind): Response
    {
        $query = $invoiceKind->query();
        $invoiceKind = $query->get();
        return $this->response()->collection($invoiceKind, new InvoiceKindTransformer());
    }

    public function costKindQuery(ContractCostKind $contractCostKind): Response
    {
        $query = $contractCostKind->query();
        $contractCostKind = $query->get();
        return $this->response()->collection($contractCostKind, new ContractCostKindTransformer());
    }

    public function goodsServiceQuery(GoodsService $goodsService, Request $request): Response
    {
        $query = $goodsService->query();
        $goodsService = $query->where('invoice_kind_id', $request->get('invoice_kind_id'))->get();
        return $this->response()->collection($goodsService, new GoodsServiceTransformer());
    }

    public function bdManagerQuery(): Response
    {
        $role = Role::findByName('bd-manager');
        $users = $role->users()->get();
        return $this->response()->collection($users, new UserTransformer());
    }

    public function legalManagerQuery(): Response
    {
        $role = Role::findByName('legal-affairs-manager');
        $users = $role->users()->get();
        return $this->response()->collection($users, new UserTransformer());
    }

    public function invoiceCompanyQuery(Request $request, InvoiceCompany $invoiceCompany): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query = $invoiceCompany->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        if ($user->hasRole('user|bd-manager')) {
            $query->where('user_id', $user->id);
        }
        $invoiceCompany = $query->get();
        return $this->response()->collection($invoiceCompany, new InvoiceCompanyTransformer());
    }

    public function paymentPayeeQuery(Request $request, PaymentPayee $paymentPayee): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query = $paymentPayee->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        if ($user->hasRole('user') || $user->hasRole('bd-manager')) {
            $query->where('user_id', $user->id);
        }
        $paymentPayee = $query->get();
        return $this->response()->collection($paymentPayee, new PaymentPayeeTransformer());
    }

    public function receiveDateQuery(Request $request, ContractReceiveDate $contractReceiveDate): Response
    {
        $query = $contractReceiveDate->query();
        $contractReceiveDate = $query->where('contract_id', $request->get('id'))
            ->whereRaw('invoice_receipt_id is null')->get();
        return $this->response()->collection($contractReceiveDate, new ContractReceiveDateTransformer());
    }

    public function userQuery(Request $request, User $user): Response
    {
        $query = $user->query();
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        $user = $query->get();
        return $this->response()->collection($user, new UserTransformer());
    }

    /**
     * 查询 拥有某个权限的用户
     * @param Request $request
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    public function userPermissionQuery(Request $request, User $user): Response
    {
        $permission_name = $request->get('permission') ?? '';

        $query = $user->query()->permission($permission_name);
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }
        $user = $query->get();

        return $this->response()->collection($user, new \App\Http\Controllers\Admin\Common\V1\Transformer\UserTransformer());
    }

    /**
     * 查询需求申请列表
     * @param Request $request
     * @param DemandApplication $demandApplication
     * @return \Dingo\Api\Http\Response
     * @throws \Exception
     */
    public function demandApplicationQuery(Request $request, DemandApplication $demandApplication): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $query = $demandApplication->query();

        if ($request->has('status')) {
            $status = explode(',', $request->get('status'));
            $query->whereIn('status', $status);
        }

        if ($request->has('no_status')) {
            $query->where('status', '!=', $request->get('no_status'));
        }

        if ($user->hasRole('bd-manager') || $user->hasRole('user') || $user->hasRole('business-operation')) {
            if (!$request->get('create_select') && $user->hasRole('bd-manager')) {
                //BD主管可查看自己及下属BD新建的申请列表
                $user_ids = $user->subordinates()->pluck('id')->toArray();
                $user_ids [] = $user->id;
                $query->whereIn('applicant_id', $user_ids);
            } else {
                //只能查询自己创建的 Application
                $query->where('applicant_id', '=', $user->id);
            }
        }

        $demandApplication = $query->orderBy('id')->get();

        return $this->response()->collection($demandApplication, new DemandApplicationTransformer());
    }


    public function teamRateQuery(TeamRate $teamRate): Response
    {
        $query = $teamRate->query();
        $teamRate = $query->get();
        return $this->response()->collection($teamRate, new TeamRateTransformer());
    }

    public function attributeQuery(): Response
    {
        /** @var \Baum\Node $node */
        $node = Attribute::query()->where('name', '业态')->first();
        $attribute = $node->getDescendants();
        return $this->response()->collection($attribute, new AttributeTransformer());
    }


    public function permissionQuery(Request $request)
    {
        $permission = Permission::query()
            ->where('guard_name', $request->get('guard_name'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->toHierarchy();
        return response()->json($permission);
    }

    public function roleQuery(Request $request, Role $role): Response
    {
        /** @var  $user \App\Models\User */
        $user = $this->user();
        $query = $role->query();
        if (!$user->isSuperAdmin()) {
            $query->where('name', '<>', 'super-admin');
        }
        $role = $query->where('guard_name', $request->get('guard_name'))->get();
        return $this->response()->collection($role, new RoleTransformer());
    }


    public function warehouseQuery(): \Illuminate\Support\Collection
    {
        return DB::table('erp_warehouses')->get();
    }

    public function erpAttributeQuery(): Response
    {
        $attribute = ErpAttribute::query()->get();
        return $this->response()->collection($attribute, new ErpAttributeTransformer());
    }

    public function erpSupplierQuery(Company $company): Response
    {
        $query = $company->query();
        $companies = $query->where('category', '=', 1)->get();
        return $this->response()->collection($companies, new CompanyTransformer());
    }

    public function erpSkuQuery(): \Illuminate\Support\Collection
    {
        return DB::table('erp_products')->select('id', 'sku')->get();
    }

    public function erpLocationQuery(): \Illuminate\Support\Collection
    {
        return DB::table('erp_locations')->select('id', 'name')->get();
    }


    public function bdAndBdManagerQuery(): Response
    {
        $bdRole = Role::findByName('user');
        $bds = $bdRole->users()->get();

        $bdManagerRole = Role::findByName('bd-manager');
        $bdManagers = $bdManagerRole->users()->get();

        $merged = $bds->merge($bdManagers);

        return $this->response()->collection($merged, new UserTransformer());
    }

    public function storeQuery(Request $request, Store $store): Response
    {
        $query = $store->query();

        //公司以及子公司名下商户列表
        if ($request->has('company_id')) {
            $company_id = $request->get('company_id');

            $query->where(static function ($q) use ($company_id) {
                $q->where('company_id', $company_id);
            })->orWhere(static function ($q) use ($company_id) {
                $q->whereHas('company', static function ($q) use ($company_id) {
                    $q->where('parent_id', $company_id);
                });
            });
        }

        if ($request->has('market_id')) {
            $query->where('marketid', '=', $request->get('market_id'));
        }

        $stores = $query->get();

        return $this->response()->collection($stores, new StoreTransformer());
    }

    public function marketConfigQuery(Request $request, MarketConfig $marketConfig)
    {
        $query = $marketConfig->query();

        if ($request->has('company_id')) {
            $query->where('company_id', '=', $request->get('company_id'));
        }

        $marketConfigs = $query->get();

        $markets = collect();
        $marketConfigs->each(static function ($item) use ($markets) {
            if ($item->market) {
                $markets->push($item->market);
            }
        });

        if ($markets->isEmpty()) {
            return null;
        }

        return $this->response()->collection($markets, new MarketTransformer());
    }

    public function playingTypeQuery(PlayingType $playingType): Response
    {
        $query = $playingType->query();
        $playingTypes = $query->orderByDesc('aid')->get();
        return $this->response()->collection($playingTypes, new PlayingTypeTransformer());
    }

    public function adminCustomersQuery(Request $request, Customer $customer): Response
    {
        $query = $customer->query();
        $company = Company::query()->findOrFail($request->get('company_id'));

        $customers = $query->whereHas('company', static function ($q) use ($company) {
            $q->where('id', $company->id);
        })->orderByDesc('id')->get();

        return $this->response()->collection($customers, new CustomerTransformer());
    }


    /**
     * 查询 拥有某个角色的customers
     * @param string $role_name
     * @param Request $request
     * @param Customer $customer
     * @return \Dingo\Api\Http\Response
     */
    public function adminCustomersQueryByRole($role_name = '', Request $request, Customer $customer): Response
    {
        $query = $customer->query()->role($role_name);

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%');
        }

        $customer = $query->get();

        return $this->response()->collection($customer, new CustomerTransformer());
    }

    /**
     * 授权节目下拉列表
     * @param Request $request
     * @param Project $project
     * @return mixed
     */
    public function authorizedProjectQuery(Request $request, Project $project)
    {
        $query = $project->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%')->get();
        }

        $projects = $query->get();
        return $this->response()->collection($projects, new ProjectTransformer());
    }

    /**
     * 授权点位下拉列表
     * @param Request $request
     * @param Point $point
     * @return mixed
     */
    public function authorizedPointQuery(Request $request, Point $point)
    {
        $query = $point->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%')->get();
        }

        if ($request->has('market_id')) {
            $query->where('marketid', $request->get('market_id'));
        }

        $points = $query->get();
        return $this->response()->collection($points, new PointTransformer());
    }


    /**
     * 授权投放策略查询
     * @param Policy $policy
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     */
    public function authorizedPolicyQuery(Policy $policy, Request $request): Response
    {
        $query = $policy->query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->get('name') . '%')->get();
        }

        $policies = $query->get();
        return $this->response()->collection($policies, new PolicyTransformer());
    }

    /**
     * 可用皮肤搜索
     * @param Skin $skin
     * @param Request $request
     * @return Response
     */
    public function projectSkinQuery(Skin $skin, Request $request): Response
    {
        $query = $skin->query();
        if ($request->has('project_id')) {
            $piid = $request->get('project_id');
            $query->whereRaw("(piid='$piid' and  pass=1)");
        }
        $skin = $query->orWhere('bid', 0)->orderBy('bid')->get();
        return $this->response()->collection($skin, new SkinTransformer());
    }

}
