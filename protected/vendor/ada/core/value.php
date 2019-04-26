<?php

namespace Ada\Core;

class Value extends Proto
{
    protected static
        $config = [
            'types' => [
                'array'    => [],
                'bool'     => false,
                'float'    => 0.0,
                'int'      => 0,
                'null'     => null,
                'object'   => null,
                'resource' => null,
                'string'   => ''
            ]
        ];

    public static function array($value): array
    {
        return (array) $value;
    }

    public static function base64($value): string
    {
        return
            (string) preg_replace(
                '/[^a-z0-9\/+=]/i',
                '',
                static::string($value)
            );
    }

    public static function base64s($values): array
    {
        return static::multi('base64', $values);
    }

    public static function bool($value): bool
    {
        return (bool) $value;
    }

    public static function bools($values): array
    {
        return static::multi('bool', $values);
    }

    public static function cmd($value): string
    {
        return
            strtolower(
                preg_replace(
                    '/[^a-z0-9_\.-]/i',
                    '',
                    static::string($value)
                )
            );
    }

    public static function cmds($values): array
    {
        return static::multi('cmd', $values);
    }

    public static function email($value): string
    {
        $value = Str::trim(static::string($value), '@');
        $pos   = strpos($value, '@');
        return
            $pos === false
                ? ''
                : (
                    substr($value, 0, $pos + 1) .
                    str_replace('@', '', substr($value, $pos + 1))
                );
    }

    public static function emails($values): array
    {
        return static::multi('email', $values);
    }

    public static function float($value): float
    {
        return
            (float) filter_var(
                static::string($value),
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
    }

    public static function floats($values): array
    {
        return static::multi('float', $values);
    }

    public static function getType($value): string
    {
        $res = static::cmd(
            gettype(
                static::typify($value)
            )
        );
        switch ($res) {
            case 'boolean':
                return 'bool';
            case 'double':
                return 'float';
            case 'integer':
                return 'int';
            default:
                return $res;
        }
    }

    public static function html($value): string
    {
        $value = Str::trim(static::string($value));
        return
            preg_match('//u', $value)
                ? $value
                : htmlspecialchars_decode(
                    htmlspecialchars($value, ENT_IGNORE, 'UTF-8')
                );
    }

    public static function htmls($values): array
    {
        return static::multi('html', $values);
    }

    public static function int($value): int
    {
        return
            (int) filter_var(
                static::string($value),
                FILTER_SANITIZE_NUMBER_INT
            );
    }

    public static function ints($values): array
    {
        return static::multi('int', $values);
    }

    public static function path($value): string
    {
        return
            Str::trim(
                strtolower(
                    preg_replace(
                        '/[\/\\\]+/',
                        '/',
                        static::string($value)
                    )
                ),
                '/'
            );
    }

    public static function paths($values): array
    {
        return static::multi('path', $values);
    }

    public static function string($value): string
    {
        return
            is_object($value) && !method_exists($value, '__toString')
                ? serialize($value)
                : (string) $value;
    }

    public static function strings($values): array
    {
        return static::multi('string', $values);
    }

    public static function typify($value, bool $recursively = true)
    {
        if ($recursively) {
            $is_array = is_array($value);
            if ($is_array || is_object($value)) {
                foreach ($value as $k => $v) {
                    $v = static::typify($v, $recursively);
                    $is_array
                        ? $value[$k] = $v
                        : $value->$k = $v;
                }
                return $value;
            }
        }
        return
            is_numeric($value)
                ? $value * 1
                : $value;
    }

    public static function ufloat($value): float
    {
        return abs(static::float($value));
    }

    public static function ufloats($values): array
    {
        return static::multi('ufloat', $values);
    }

    public static function uint($value): int
    {
        return abs(static::int($value));
    }

    public static function uints($values): array
    {
        return static::multi('uint', $values);
    }

    public static function url($value, array $parts = null): string
    {
        return Url::init(static::string($value))->out($parts);
    }

    public static function urlPart($value): string
    {
        return Url::clean($value);
    }

    public static function urls($values): array
    {
        return static::multi('url', $values);
    }

    protected static function multi(string $method, $values): array
    {
        return array_map([__CLASS__, $method], static::array($values));
    }
}
