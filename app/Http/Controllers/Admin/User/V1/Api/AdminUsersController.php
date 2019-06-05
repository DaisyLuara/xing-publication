<?php

namespace App\Http\Controllers\Admin\User\V1\Api;

use App\Http\Controllers\Admin\Common\V1\Models\Image;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Contract\V1\Models\Contract;
use App\Http\Controllers\Admin\Demand\V1\Models\DemandApplication;
use App\Http\Controllers\Admin\Invoice\V1\Models\Invoice;
use App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceCompany;
use App\Http\Controllers\Admin\Payment\V1\Models\Payment;
use App\Http\Controllers\Admin\Payment\V1\Models\PaymentPayee;
use App\Http\Controllers\Admin\Point\V1\Models\Point;
use App\Http\Controllers\Admin\User\V1\Models\ArMemberPermission;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Admin\User\V1\Transformer\UserTransformer;
use App\Http\Controllers\Admin\User\V1\Request\UserRequest;
use App\Models\User;
use App\Http\Controllers\Controller;
use Dingo\Api\Http\Response;
use Illuminate\Http\Request;
use App\Jobs\CreateAdminStaffJob;
use DB;

class AdminUsersController extends Controller
{

    public function show($user_id): Response
    {
        return $this->response()->item($this->getUserByID($user_id), new UserTransformer());
    }

    public function index(Request $request, User $user): Response
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = $user->query();
        if ($request->has('phone')) {
            $query->where('phone', 'like', $request->get('phone') . '%');
        }

        if ($request->has('name')) {
            $query->where('name', 'like', $request->get('name') . '%');
        }

        if ($request->has('role_id')) {
            $query->whereHas('roles', static function ($q) use ($request) {
                $q->where('id', $request->get('role_id'));
            });
        }

        $users = $query->whereHas('roles', static function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->orderByDesc('id')->paginate(10);

        return $this->response()->paginator($users, new UserTransformer());
    }

    /**
     * @param UserRequest $request
     * @return Response|void
     */
    public function store(UserRequest $request)
    {
        //保证传入的角色不会超过本身的权限
        $role = $this->user()->getSystemRoles()->firstWhere('id', $request->get('role_id'));
        if ($role === null) {
            return $this->response()->errorNotFound('角色不存在');
        }

        if (($role->name === 'legal-affairs' || $role->name === 'user') && !$request->has('parent_id')) {
            abort(500, '请选择所属主管');
        }

        /** @var User $user */
        $user = User::create([
            'name' => $request->get('name'),
            'phone' => $request->get('phone'),
            'password' => bcrypt($request->get('password')),
            'parent_id' => $request->get('parent_id')
        ]);

        $user->assignRole($role);

        if ($role->name === 'bd-manager' || $role->name === 'legal-affairs-manager') {
            $user->parent_id = $user->id;
            $user->update();
        }
        activity('user')
            ->causedBy($this->user())
            ->on($user)
            ->withProperties($request->except(['password']))
            ->log('新增用户');

        CreateAdminStaffJob::dispatch($user, $role)->onQueue('create_admin_staff');

        //@todo 关联创建EXE LOOK 用户
        return $this->response()->item($user, new UserTransformer())->setStatusCode(201);
    }

    public function update($user_id, UserRequest $request): Response
    {
        $user = $this->getUserByID($user_id);
        $currentUser = $this->user();

        //传入的权限不会超过管理员本身权限
        $role = $currentUser->getSystemRoles()->firstWhere('id', $request->get('role_id'));
        if ($role && $request->get('role_id')) {
            $user->syncRoles($role);
        }

        $attributes = $request->only(['name', 'phone', 'parent_id']);
        if ($request->has('avatar_image_id')) {
            $image = Image::find($request->get('avatar_image_id'));
            $attributes['avatar'] = $image->path;
        }

        if ($request->has('password')) {
            $attributes['password'] = bcrypt($request->get('password'));
        }

        $user->update($attributes);

        activity('user')
            ->causedBy($currentUser)
            ->on($user)
            ->withProperties($request->except(['password']))
            ->log('修改用户');

        return $this->response()->item($user, new UserTransformer());
    }

    private function getUserByID($user_id)
    {
        $isSuperAdmin = $this->user()->isSuperAdmin();

        $query = User::query();
        return $query->whereHas('roles', function ($q) use ($isSuperAdmin) {
            if (!$isSuperAdmin) {
                $q->where('name', '<>', 'super-admin');
            }
        })->where('id', '=', $user_id)->first();
    }

    public function destroy($user_id)
    {
        $user = User::findOrFail($user_id);
        $currentUser = $this->user();

        if ($currentUser->id === $user_id) {
            abort(403);
        }

        $user->delete();
        return $this->response()->noContent();
    }

    public function syncZValue($user_id)
    {
        $user = $this->getUserByID($user_id);
        abort_if(!$user, 404, '用户不存在！');

        $zValue = ArMemberPermission::query()->where('mobile', $user->phone)->first(['z']);
        abort_if(!$zValue, 404, '请联系星动力系统管理员分配Z值!');

        $user->update($zValue->toArray());

        return $this->response()->noContent();

    }

    public function transfer(Request $request, User $user): Response
    {
        if (!$user->hasRole('user|bd-manager')) {
            abort('迁移只适用于bd');
        }

        if ($user->hasRole('bd-manager') && $user->subordinates->count() > 1) {
            abort(403, '有下属人员不可迁移');
        }

        $userId = $request->get('user_id');
        $newUser = User::find($userId);
        if (!($newUser && $newUser->hasRole('user|bd-manager'))) {
            abort(404, '移交的bd不存在');
        }

        if (!($user->z && $newUser->z)) {
            abort(403, '无用户标识');
        }

        //点位迁移
        $points = Point::query()->where('bd_z', $user->z)->get();
        $arUser = ArUser::query()->where('z', $newUser->z)->first();
        foreach ($points as $point) {
            $point->update(['bd_uid' => $arUser->uid, 'bd_z' => $arUser->z]);
        }

        //流程相关迁移
        try {
            DB::beginTransaction();

            $contracts = Contract::query()->where('owner', $user->id)->get();
            foreach ($contracts as $contract) {
                $contract->update(['owner' => $newUser->id]);
            }

            $invoices = Invoice::query()->where('owner', $user->id)->get();
            foreach ($invoices as $invoice) {
                $invoice->update(['owner' => $newUser->id]);
            }

            $invoiceCompanies = InvoiceCompany::query()->where('owner', $user->id)->get();
            foreach ($invoiceCompanies as $invoiceCompany) {
                $invoiceCompany->update(['owner' => $newUser->id]);
            }

            $payments = Payment::query()->where('owner', $user->id)->get();
            foreach ($payments as $payment) {
                $payment->update(['owner' => $newUser->id]);
            }

            $paymentPayees = PaymentPayee::query()->where('owner', $user->id)->get();
            foreach ($paymentPayees as $paymentPayee) {
                $paymentPayee->update(['owner' => $newUser->id]);
            }

            $companies = Company::query()->where('bd_user_id', $user->id)->get();
            foreach ($companies as $company) {
                $company->update(['bd_user_id' => $newUser->id]);
            }

            $demands = DemandApplication::query()->where('owner', $user->id)->get();
            foreach ($demands as $demand) {
                $demand->update(['owner' => $newUser->id]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e);
            abort(500, '系统错误');
        }
        return $this->response()->noContent();
    }
}
