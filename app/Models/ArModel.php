<?php

namespace App\Models;


/**
 * App\Models\ArModel
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model ordered()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\ArModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Model recent()
 * @mixin \Eloquent
 */
class ArModel extends Model
{
    protected $connection = 'ar';
    public $timestamps = false;

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->date = date('Y-m-d H:i:s');
            $model->clientdate = time() * 1000;
            return true;
        });

        /**
         * to fire updating must use Eloquent Model
         *   e.g use for one to one relationship
         *      $user = User::find(1)
         *      $account = $user->account()->getResults()->update($request->account)
         */
        static::updating(function ($model) {
            $model->date = date('Y-m-d H:i:s');
            $model->clientdate = time() * 1000;
            return true;
        });

    }
}
