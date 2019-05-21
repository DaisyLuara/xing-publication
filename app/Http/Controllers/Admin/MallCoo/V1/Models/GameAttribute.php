<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Models\Model;
use DB;


class GameAttribute extends Model
{
    public $connection = 'jingsaas';

    protected $table = 'game_attribute';

    protected $fillable = [];

    public function game_result()
    {
        return $this->belongsTo(GameResult::class, 'game_attribute_id', 'id');
    }

}
