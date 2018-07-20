<?php

namespace App\Observers;

use App\Http\Controllers\Admin\ShortUrl\V1\Models\ShortUrlRecords;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ShortUrlRecordObserver
{
    public function saving(ShortUrlRecords $shortUrlRecords)
    {
        if (!$shortUrlRecords->face_id) {
            $shortUrlRecords->face_id = 0;
        }

    }

    public function updating(ShortUrlRecords $shortUrlRecords)
    {
        if (!$shortUrlRecords->face_id) {
            $shortUrlRecords->face_id = 0;
        }
    }
}