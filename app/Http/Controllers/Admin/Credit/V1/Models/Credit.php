<?php

namespace App\Http\Controllers\Admin\Credit\V1\Models;

use App\Models\ArModel;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * 用户分值
 * Class Credit
 * @package App\Http\Controllers\Admin\Credit\V1\Models
 * @property string $mobile
 * @property int $p_groupid
 * @property int $p_credits
 * @property int $p_money
 * @property int $p_rmb
 * @property int $p_rep
 */
class Credit extends ArModel
{
    protected $table = 'news_user_permission';
    protected $primaryKey = 'uid';

    protected $fillable = ['mobile', 'p_groupid', 'p_credits', 'p_money', 'p_rmb', 'p_rep'];

    public function user_group(): BelongsTo
    {
        return $this->belongsTo(UserGroup::class, 'p_groupid', 'groupid');
    }

    public function customer(): BelongsTo
    {
        return $this->setConnection('mysql')->belongsTo(Customer::class, 'mobile', 'phone');
    }

}
