<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/11/22
 * Time: 上午10:18
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FacePlaytimesCharacterRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FacePlaytimesCharacterRecord extends Model
{
    //7s,15s,21s人群特征清洗记录表
    protected $fillable = ['date'];
}