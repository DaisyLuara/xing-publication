<?php
/**
 * Created by PhpStorm.
 * User: yangqiang
 * Date: 2018/5/17
 * Time: 20:26
 */

namespace App\Transformers;


use App\Models\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'id' => $product->pid,
            'img' => $product->img,
            'name' => $product->pname,
            'app_key' => $product->app_key,
            'db_num' => $product->ar_db_num,
            'ar_num' => $product->ar_num,
            'crow_num' => $product->face_crow_num,
            'people_num' => $product->face_people_num,
            'stid' => $product->stid,
            'date' => $product->date,
        ];
    }

}