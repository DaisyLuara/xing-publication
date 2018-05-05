<?php

namespace App\Models;


class Customer extends Model
{

    protected $fillable = ['name', 'phone', '', 'address', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
