<?php

namespace App\Http\Controllers\Admin\Attribute\V1\Models;

use App\Models\Model;
use Baum\Node;


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
