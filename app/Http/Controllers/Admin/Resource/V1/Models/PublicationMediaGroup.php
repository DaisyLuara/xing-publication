<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2019/5/7
 * Time: 下午3:46
 */

namespace App\Http\Controllers\Admin\Resource\V1\Models;


use App\Models\Model;

/**
 * App\Http\Controllers\Admin\Resource\V1\Models\PublicationMediaGroup
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMediaGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMediaGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMediaGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|Model recent()
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMediaGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PublicationMediaGroup whereName($value)
 * @mixin \Eloquent
 */
class PublicationMediaGroup extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
}