<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/11
 * Time: 10:51
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooknumCharacterRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaceLooknumCharacterRecord extends Model
{
    protected $fillable = ['date'];
}