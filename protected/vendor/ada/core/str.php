<?php

namespace Ada\Core;

class Str extends Proto
{
    protected static
        $config = [
            'trim_chars' => " \t\n\r\0\x0B"
        ];

    public static function trim(
        string $string,
        string $character_mask_postfix = ''
    ): string
    {
        return
            trim(
                $string,
                static::config()->getString('trim_chars') . $character_mask_postfix
            );
    }
}
