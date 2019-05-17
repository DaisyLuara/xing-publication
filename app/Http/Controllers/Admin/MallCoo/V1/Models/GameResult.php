<?php

namespace App\Http\Controllers\Admin\MallCoo\V1\Models;

use App\Models\Model;


class GameResult extends Model
{
    public $connection = 'jingsaas';

    protected $table = 'game_result';

    protected $fillable = [];

    public function gameAttribute()
    {
        return $this->belongsTo(GameAttribute::class, 'game_attribute_id', 'id');
    }

}
