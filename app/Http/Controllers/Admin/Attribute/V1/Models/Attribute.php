<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;
use Baum\Node;


/**
 * App\Http\Controllers\Admin\Attribute\V1\Models\Attribute
 *
 * @property int $id 属性配置
 * @property int $parent_id 分类父节点
 * @property string $name 属性名称
 * @property string $desc 属性描述
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $lft
 * @property int|null $rgt
 * @property int|null $depth
 * @property-read \Baum\Extensions\Eloquent\Collection|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute[] $children
 * @property-read \App\Http\Controllers\Admin\Attribute\V1\Models\Attribute $parent
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node limitDepth($limit)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereDepth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereDesc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereLft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereRgt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Http\Controllers\Admin\Attribute\V1\Models\Attribute whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutNode($node)
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutRoot()
 * @method static \Illuminate\Database\Eloquent\Builder|\Baum\Node withoutSelf()
 * @mixin \Eloquent
 */
class Attribute extends Node
{
    protected $connection = 'ar';
    public $table = 'xs_attributes';

    // 'parent_id' column name
    protected $parentColumn = 'parent_id';

    // 'lft' column name
    protected $leftColumn = 'lft';

    // 'rgt' column name
    protected $rightColumn = 'rgt';

    // 'depth' column name
    protected $depthColumn = 'depth';

    // guard attributes from mass-assignment
    protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');
}
