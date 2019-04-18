<?php

namespace Ada\Core;

class Type extends Proto
{
    protected static
        $config = [
            'initial_values' => [
                'array'    => [],
                'bool'     => false,
                'float'    => 0.0,
                'int'      => 0,
                'null'     => null,
                'object'   => null,
                'resource' => null,
                'string'   => ''
            ],
            'names' => [
                'array' => [
                    'arr',
                    'array'
                ],
                'bool' => [
                    'bool',
                    'boolean'
                ],
                'float' => [
                    'float',
                    'double'
                ],
                'int' => [
                    'int',
                    'integer'
                ],
                'null' => [
                    'null'
                ],
                'object' => [
                    'obj',
                    'object'
                ],
                'resource' => [
                    'resource'
                ],
                'string' => [
                    'str',
                    'string'
                ]
            ]
        ];

    public static function arr($value): array
    {
        return (array) $value;
    }

    public static function bool($value): bool
    {
        return (bool) $value;
    }

    public static function float($value): float
    {
        return (float) $value;
    }

    public static function fullName(string $alias): string
    {
        return
            (string) key(
                array_filter(
                    static::$config['names'],
                    function($el) use($alias) {
                        return in_array(Clean::cmd($alias), $el);
                    }
                )
            );
    }

    public static function get($value): string
    {
        return
            static::fullName(
                gettype(is_numeric($value) ? $value * 1 : $value)
            );
    }

    public static function int($value): int
    {
        return (int) $value;
    }

    public static function obj($value)
    {
        return (object) $value;
    }

    public static function str($value): string
    {
        if (!is_object($value)) {
            return (string) $value;
        }
        if (!method_exists($value, '__toString')) {
            return '';
        }
        $res = $value->__toString();
        if ($res === $value) {
            return '';
        }
        return static::str($res);
    }














    public static function set($value, string $type = 'auto', bool $recursively = true)
    {
        if ($recursively) {
            $is_array = is_array($value);
            if ($is_array || is_object($value)) {
                foreach ($value as $k => $v) {
                    $v = static::set($v, $type, true);
                    $is_array
                        ? $value[$k] = $v
                        : $value->$k = $v;
                }
                return $value;
            }
        }
        $alias     = Clean::cmd($type);
        $full_name = $alias === 'auto' ? static::get($value) : static::fullName($alias);
        if (!$full_name) {
            throw new Exception('Unknown type \'' . $type . '\'', 1);
        }
        settype($value, $full_name);
        return  $value;
    }
}
