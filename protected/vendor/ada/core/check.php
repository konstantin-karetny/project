<?php

namespace Ada\Core;

class Check extends Proto
{
    public static function base64(string $value): bool
    {
        return base64_encode(base64_decode($value, true)) === $value;
    }

    public static function bool($value): bool
    {
        $res = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        return ($res === true || $res === false) ? true : false;
    }

    public static function cmd(string $value): string
    {
        return
            strtolower(
                preg_replace('/[^a-z0-9_\.-]/i', '', $value)
            );
    }

    public static function email(string $value): string
    {
        return (string) filter_var($value, FILTER_SANITIZE_EMAIL);
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
