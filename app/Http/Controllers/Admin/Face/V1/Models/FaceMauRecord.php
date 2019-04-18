<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/11
 * Time: 10:56
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceMauRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaceMauRecord extends Model
{
    protected $fillable = ['date'];
}