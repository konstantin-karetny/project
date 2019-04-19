<?php

namespace Ada\Core;

class Str extends Proto
{
    protected static
        $config = [
            'trim_character_mask' => " \t\n\r\0\x0B"
        ];

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
