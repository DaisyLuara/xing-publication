<?php

namespace App\Models;

use App\Http\Controllers\Admin\Company\V1\Models\Company;
use App\Http\Controllers\Admin\Privilege\V1\Models\Role;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class User
 *
 * @package App\Models
 * @property int $id
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $password
 * @property string|null $weixin_openid
 * @property string|null $weixin_unionid
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $avatar
 * @property string|null $introduction
 * @property int|null $ar_user_id 星视度用户ID
 * @property string|null $z ar用户标识
 * @property int $notification_count
 * @property string|null $tower_access_token
 * @property string|null $tower_refresh_token
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $activity
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Company\V1\Models\Company[] $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Activitylog\Models\Activity[] $loggedActivity
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Http\Controllers\Admin\Privilege\V1\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Http\Controllers\Admin\Privilege\V1\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|User[] $subordinates
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereArUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIntroduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNotificationCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTowerAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTowerRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWeixinOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereWeixinUnionid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereZ($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements JWTSubject
{

    use HasRoles;
    use CausesActivity;
    use SoftDeletes;

    use Notifiable {
        notify as protected laravelNotify;
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'email', 'password', 'phone', 'avatar', 'introduction', 'ar_user_id', 'tower_access_token', 'tower_refresh_token', 'z'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 得到所有的下属
     * @return mixed
     */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function company()
    {
        return $this->hasMany(Company::class);
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {

            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }

        $this->attributes['password'] = $value;
    }

    public function setAvatarAttribute($path)
    {
        // 如果不是 `http` 子串开头，那就是从后台上传的，需要补全 URL
        if (!starts_with($path, 'http')) {

            // 拼接完整的 URL
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }

        $this->attributes['avatar'] = $path;
    }

    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    //超级管理员
    public function isSuperAdmin()
    {
        return $this->hasRole('super-admin');
    }

    //普通管理员
    public function isAdmin()
    {
        return $this->hasRole(['super-admin', 'admin']);
    }

    //销售人员
    public function isBD()
    {
        return $this->hasRole('user|bd-manager');
    }

    //系统配置 可选角色
    public function getSystemRoles()
    {
        return $this->isSuperAdmin() ? Role::all() : Role::where('name', '<>', 'super-admin')->get();
    }

    //可查看操作记录的角色
    public function canSeeOperateLog()
    {
        return $this->hasRole(['super-admin', 'admin','legal-affairs-manager']);
    }
}
