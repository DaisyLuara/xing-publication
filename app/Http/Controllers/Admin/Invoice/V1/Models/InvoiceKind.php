<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/28
 * Time: 上午10:54
 */

namespace App\Http\Controllers\Admin\Invoice\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Invoice\V1\Models\InvoiceKind whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InvoiceKind extends Model
{
    protected $fillable = ['name'];
}