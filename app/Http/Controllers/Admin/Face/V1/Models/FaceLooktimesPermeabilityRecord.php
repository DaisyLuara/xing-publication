<?php
/**
 * Created by PhpStorm.
 * User: zhangjingjing
 * Date: 2018/11/16
 * Time: 上午11:48
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceLooktimesPermeabilityRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaceLooktimesPermeabilityRecord extends Model
{
    protected $fillable = ['date'];
}