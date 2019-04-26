<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/4/26
 * Time: 下午6:00
 */

namespace App\Http\Controllers\Admin\Media\V1\Models;


use App\Models\Model;


/**
 * App\Http\Controllers\Admin\Media\V1\Models\ActivityMedia
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|ActivityMedia query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @mixin \Eloquent
 */
class ActivityMedia extends Model
{
    protected $fillable = [
        'name',
        'size',
        'url',
        'status',
    ];
}