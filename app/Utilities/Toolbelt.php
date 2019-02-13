<?php
/**
 * Created by PhpStorm.
 * User: kyletaylor
 * Date: 2019-02-12
 * Time: 19:48
 */

namespace App\Utilities;


class Toolbelt
{

    public static function toSatoshi($value) 
    {
        return number_format($value, 8);
    }
}