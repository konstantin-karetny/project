<?php

namespace Ada\Core;

class Arr extends Proto
{
    public static function explodeKey(string $key)
    {
        return explode('[', str_replace(']', '', $key));
    }

    public static function first(array $array)
    {
        return array_shift($array);
    }

    public static function isset(array $array, string $key): bool
    {
        $res = $array;
        foreach (static::explodeKey($key) as $k) {
            if (!isset($res[$k])) {
                return false;
            }
            $res = $res[$k];
        }
        return true;
    }

    public static function last(array $array)
    {
        return array_pop($array);
    }

    public static function unset(array &$array, string $key): void
    {
        $keys    = static::explodeKey($key);
        $last_k  = static::last($keys);
        $closure = function (&$array, $k) use (&$closure, &$keys, $last_k) {
            if (!isset($array[$k])) {
                return;
            }
            if ($k === $last_k) {
                unset($array[$k]);
                return;
            }
            $closure($array[$k], next($keys));
        };
        $closure($array, reset($keys));
    }

    public static function value(array $array, string $key)
    {
        $res = $array;
        foreach (static::explodeKey($key) as $k) {
            if (!isset($res[$k])) {
                return;
            }
            $res = $res[$k];
        }
        return $res;
    }
}
