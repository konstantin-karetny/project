<?php

namespace Ada\Core;

class Arr extends Proto
{
    public static function isset(array $array, string $key)
    {
        $res = $array;
        foreach (explode('[', str_replace(']', '', $key)) as $k) {
            if (!isset($res[$k])) {
                return false;
            }
            $res = $res[$k];
        }
        return true;
    }

    public static function value(array $array, string $key)
    {
        $res = $array;
        foreach (explode('[', str_replace(']', '', $key)) as $k) {
            if (!isset($res[$k])) {
                return;
            }
            $res = $res[$k];
        }
        return $res;
    }
}
