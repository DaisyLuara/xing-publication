<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/7/11
 * Time: 11:47
 */

namespace App\Http\Controllers\Admin\Face\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord
 *
 * @property int $id
 * @property string|null $date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Face\V1\Models\FaceCountRecord whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FaceCountRecord extends Model
{
    protected $fillable = ['date'];
}