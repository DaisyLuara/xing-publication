<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\carbon;
use Spatie\Permission\Models\Permission;

class AddWechatcardPermissonToSpecialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'wechat_card', 'display_name' => '微信卡券']);

        $users = User::query()->whereIn('name', ['方圆', '陈重', '赵俊', '刘洪亚', '张诗瑶','测试'])->get();
        foreach ($users as $user) {
            $user->givePermissionTo('wechat_card');
        }
    }
}
