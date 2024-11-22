<?php

namespace app\helpers;

use Exception;

class DrawHelper
{
    public static function isPowerOfTwo($n) {
        return $n > 0 && ($n & ($n - 1)) === 0;
    }
    public static function sortByScore($arrayList) {
        usort($arrayList, function($a, $b) {
            return $a['score'] <=> $b['score'];
        });
        return $arrayList;
    }
    public static function splitArray($arr) {
        if (count($arr) % 2 != 0) {
            throw new Exception("Количество команд должно быть четным");
        }
        $mid = count($arr) / 2;
        $first_half = array_slice($arr, 0, $mid);
        $second_half = array_slice($arr, $mid);
        return [$first_half, $second_half];
    }
}