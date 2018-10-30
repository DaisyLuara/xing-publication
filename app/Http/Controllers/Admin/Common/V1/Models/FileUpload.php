<?php

namespace App\Http\Controllers\Admin\Common\V1\Models;

use App\Models\Model;

class FileUpload extends Model
{
    protected $connection = 'ar';
    public $table = 'file_upload';
}
