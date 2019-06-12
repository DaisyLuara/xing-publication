<?php

namespace App\Http\Controllers\Admin\ResourceAuth\V1\Models;

use App\Http\Controllers\Admin\Project\V1\Models\Project;
use App\Http\Controllers\Admin\Skin\V1\Models\Skin;
use App\Models\ArModel;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth
 *
 * @property int $id
 * @property int|null $uid
 * @property string $z 用户标识
 * @property int $pid 产品ID
 * @property int $bid
 * @property string $date
 * @property int $clientdate
 * @property-read Customer $customer
 * @property-read \App\Http\Controllers\Admin\Project\V1\Models\Project $project
 * @property-read \App\Http\Controllers\Admin\Skin\V1\Models\Skin $skin
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereBid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\ResourceAuth\V1\Models\ProjectAuth whereZ($value)
 * @mixin \Eloquent
 */
class ProjectAuth extends ArModel
{
    public $table = 'admin_per_pid';

    public $fillable = [
        'id',
        'z',
        'pid',
        'bid',
        'date',
        'clientdate',
    ];

    public function customer(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'z', 'z');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'pid', 'id');
    }

    public function skin(): BelongsTo
    {
        return $this->belongsTo(Skin::class, 'bid', 'bid');
    }

}
