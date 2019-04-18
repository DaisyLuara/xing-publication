<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToArray;

class CouponBatchImport implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}