<?php

namespace Ada\Core;

class Proto
{
    protected static
        $cache  = [],
        $config = [];

    public static function getConfig(): array
    {
        return static::$config;
    }

    public static function init()
    {
        return new static(...func_get_args());
    }

    public static function setConfig(array $config)
    {
        static::$config = Type::set($config);
    }
}
