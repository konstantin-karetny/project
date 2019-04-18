<?php

namespace Ada\Core;

class Clean extends Proto
{
    public static function base64(string $value): string
    {
        return (string) preg_replace('/[^a-z0-9\/+=]/i', '', $value);
    }

    public static function bool($value): bool
    {
        return (bool) $value;
    }

    public static function cmd(string $value): string
    {
        return
            strtolower(
                preg_replace('/[^a-z0-9_\.-]/i', '', $value)
            );
    }

    public static function cmds(array $values): array
    {
        return array_map([__CLASS__, 'cmd'], $values);
    }

    public static function email(string $value): string
    {
        $value =   trim($value, '@');
        $pos   = strpos($value, '@');
        return
            $pos === false
                ? ''
                : (
                    substr($value, 0, $pos + 1) .
                    str_replace('@', '', substr($value, $pos + 1))
                );
    }

    public static function float(string $value): float
    {
        return
            (float) filter_var(
                $value,
                FILTER_SANITIZE_NUMBER_FLOAT,
                FILTER_FLAG_ALLOW_FRACTION
            );
    }

    public static function html(string $value): string
    {
        $value = trim($value);
        return
            preg_match('//u', $value)
                ? $value
                : htmlspecialchars_decode(
                    htmlspecialchars($value, ENT_IGNORE, 'UTF-8')
                );
    }

    public static function int(string $value): int
    {
        return (int) filter_var($value, FILTER_SANITIZE_NUMBER_INT);
    }

    public static function path(string $value): string
    {
        return
            trim(
                strtolower(
                    preg_replace('/[\/\\\]+/', '/', $value)
                ),
                '/'
            );
    }

    public static function ufloat(string $value): float
    {
        return (float) abs(static::float($value));
    }

    public static function uint(string $value): int
    {
        return (int) abs(static::int($value));
    }

    public static function url(string $value): string
    {
        return Url::init($value)->out(Url::PARTS);
    }
}
