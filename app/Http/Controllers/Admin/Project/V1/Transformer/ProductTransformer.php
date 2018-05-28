<?php

namespace App\Http\Controllers\Admin\Ad\V1\Transformer;

use App\Http\Controllers\Admin\Project\V1\Models\Product;
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