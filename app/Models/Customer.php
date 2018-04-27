<?php

namespace App\Models;


class Customer extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
