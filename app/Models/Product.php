<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 20:21
 */

namespace App\Models;


class Product extends Model
{
    protected $connection='ar';
    public $table='ar_product';
    protected $primaryKey='pid';
}