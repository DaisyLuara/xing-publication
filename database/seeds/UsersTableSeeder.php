<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ArUser;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 获取 Faker 实例
        $faker = app(Faker\Generator::class);

        // 头像假数据
        $avatars = [
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/s5ehp11z6s.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/Lhd1SHqu86.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/LOnMrqbHJn.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/xAuDMxteQy.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png?imageView2/1/w/200/h/200',
            'https://fsdhubcdn.phphub.org/uploads/images/201710/14/1/NDnzMutoxX.png?imageView2/1/w/200/h/200',
        ];

        $ar_users = ArUser::get();
        $ar_users->each(function ($ar_user) use ($faker, $avatars) {
            $user_array = [
                'avatar' => $faker->randomElement($avatars),
                'name' => $ar_user->realname,
                'phone' => $ar_user->mobile,
                'password' => bcrypt('password'),
                'ar_user_id' => $ar_user->uid,
            ];
            // 插入到数据库中
            $user = User::create($user_array);

            if (in_array($ar_user->role_id, [3, 5, 6, 7, 9, 11])) {
                $user->assignRole('user');
            } elseif (in_array($ar_user->role_id, [2])) {
                $user->assignRole('admin');
            } elseif (in_array($ar_user->role_id, [4])) {
                $user->assignRole('project-manager');
            } elseif ($ar_user->role_id == 10) {
                $user->assignRole('dev-ops');
            } elseif ($ar_user->role_id == 1) {
                $user->assignRole('super-admin');
            }

        });

        //添加管理员
        User::create([
            'avatar' => $faker->randomElement($avatars),
            'name' => '王翔',
            'phone' => '18616348089',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        User::create([
            'avatar' => $faker->randomElement($avatars),
            'name' => '赵俊',
            'phone' => '18255482472',
            'password' => bcrypt('password'),
        ])->assignRole('admin');
    }
}