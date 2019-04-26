<?php

namespace Ada\Core;

class Str extends Proto
{
    protected static
        $config = [
            'trim_character_mask' => " \t\n\r\0\x0B"
        ];

    public static function separate(
        string $string,
        string $replacement = ' \-_',
        string $separator   = ' '
    ): string
    {
        return
            (string) preg_replace(
                '/[' . $replacement . ']+/',
                $separator,
                trim(
                    preg_replace(
                        '/([A-Z])/',
                        ' $1',
                        $string
                    )
                )
            );
    }

    public static function toCamelCase(
        string $string,
        bool   $ucfirst = false
    ): string
    {
        $res = (string) str_replace(' ', '', ucwords(static::separate($string)));
        return $ucfirst ? $res : lcfirst($res);
    }

    public static function trim(
        string $string,
        string $character_mask_postfix = ''
    ): string
    {
        return
            trim(
                $string,
                (
                    static::config()->getString('trim_character_mask') .
                    $character_mask_postfix
                )
            );
    }
}
