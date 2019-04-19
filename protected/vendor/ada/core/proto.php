<?php

namespace Ada\Core;

class Proto
{
    protected static
        $cache  = [],
        $config = [];

    public static function cache(): DataSet
    {
        return
            is_object(static::$cache)
                ? static::$cache
                : static::$cache = DataSet::init(Value::array(static::$cache));
    }

    public static function config(): DataSet
    {
        return
            is_object(static::$config)
                ? static::$config
                : static::$config = DataSet::init(Value::array(static::$config));
    }

    public static function init()
    {
        return new static(...func_get_args());
    }
}
