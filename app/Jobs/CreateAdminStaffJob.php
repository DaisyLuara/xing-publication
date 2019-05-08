<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use App\Models\Customer;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use ReflectionClass;
use Xingstation\ExelookApi\Exelook;

class CreateAdminStaffJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $adminStaff;
    private $role;
    private const ROLE_MAPPING = [
        'Customer' => [
            'ad_owner' => 9,
            'market_owner' => 11,
        ],
        'User' => [
            'user' => 8,
            'bd-manager' => 8,
        ]
    ];

    /**
     * Create a new job instance.
     *
     * @param Customer|User $adminStaff
     * @param Role $role
     */
    public function __construct($adminStaff, Role $role)
    {
        $this->adminStaff = $adminStaff;
        $this->role = $role;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \ReflectionException
     */
    public function handle(): void
    {
        if (env('APP_ENV') === 'production') {
            $zValue = $this->getAdminStaffZValue();
            $this->adminStaff->update(['z' => $zValue]);
        }

    }

    /**
     * @return string
     * @throws \ReflectionException
     * @throws \Exception
     */
    private function getAdminStaffZValue(): string
    {

        $exelook = new Exelook(config('exelook'));

        $result = $exelook->user_info->gmUserInfoHttp([
            'token' => 'cz',
            'username' => app('pinyin')->permalink($this->adminStaff->name, ''),
            'mobile' => $this->adminStaff->phone,
            'id' => $this->getRoleID(),
            'face' => null,
        ]);

        return $result['z'] ?? null;
    }

    /**
     * @return int
     * @throws \ReflectionException
     */
    private function getRoleID(): int
    {
        $class = new ReflectionClass($this->adminStaff);
        $shortName = $class->getShortName();

        abort_if(!array_key_exists($shortName, self::ROLE_MAPPING), 500, '获取角色列表失败!');

        $roleIDMappings = self::ROLE_MAPPING[$shortName];
        abort_if(!array_key_exists($this->role->name, $roleIDMappings), 500, '获取角色失败!');

        return $roleIDMappings[$this->role->name];
    }


}
