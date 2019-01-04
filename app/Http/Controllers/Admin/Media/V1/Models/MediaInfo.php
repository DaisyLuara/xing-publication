<?php

namespace App\Http\Controllers\Admin\Media\V1\Models;

use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaInfo extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'type',
        'media_id',
        'date',
        'recorder_id'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id', 'id');
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorder_id', 'id');
    }
}
