<?php

namespace App\Http\Controllers\Admin\WeChat\V1\Models;

use App\Models\ArModel;


/**
 * App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket
 *
 * @property int $id
 * @property string $ticket 标识
 * @property string $txt 内容
 * @property string $date
 * @property int $clientdate 时间
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket whereClientdate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket whereTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\WeChat\V1\Models\ComponentVerifyTicket whereTxt($value)
 * @mixin \Eloquent
 */
class ComponentVerifyTicket extends ArModel
{
    public $table = 'wx_third_ticket';
}
