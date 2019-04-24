<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/15
 * Time: 下午3:35
 */

namespace App\Http\Controllers\Admin\Common\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor
 *
 * @property int $id
 * @property string $name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $remark
 * @property string|null $subscribe
 * @property int $type 合作对象类型:1:商业综合体&商户 2:品牌客户 3:加盟代理
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Common\V1\Models\WebsiteVisitor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WebsiteVisitor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'remark',
        'subscribe',
        'type'
    ];
}