<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\Admin\User\V1\Models\ArUser;
use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Models\Customer;

class AddCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xingstation:add_customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '快速添加用户';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mobile = $this->ask('输入用户手机号码');
        $password = $this->secret('输入新密码');

        $companyQuery = Company::query();
        $ar_user = ArUser::query()->whereIn('role_id', [9, 11])->where('mobile', $mobile)->first();
        if (!$ar_user) {
            return $this->error('用户不存在');
        }

        $company = $companyQuery->updateOrCreate(['name' => $ar_user->realname], [
            'user_id' => 1,
            'name' => $ar_user->realname,
            'address' => $ar_user->realname,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $customer_array = [
            'name' => $ar_user->realname,
            'phone' => $ar_user->mobile,
            'ar_user_id' => $ar_user->uid,
            'company_id' => $company->id,
        ];
        if ($password) {
            $customer_array['password'] = bcrypt($password);
        }

        /** @var Customer $customer */
        $customer = Customer::query()->updateOrCreate(['phone' => $ar_user->mobile], $customer_array);

        if ($ar_user->role_id == 9) {
            if (!$customer->hasRole('ad_owner')) {
                $customer->assignRole('ad_owner');
            }
        } elseif ($ar_user->role_id == 11) {
            if (!$customer->hasRole('market_owner')) {
                $customer->assignRole('market_owner');
            }
        }

    }
}
