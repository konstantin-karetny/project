<?php

namespace Ada\Core;

class Proto
{
    protected static
        $cache  = [],
        $config = [];

    public static function config(): DataSet
    {
        return
            is_object(static::$config)
                ? static::$config
                : static::$config = DataSet::init(Value::array(static::$config));
    }

    protected static function cache(): DataSet
    {
        return
            is_object(static::$cache)
                ? static::$cache
                : static::$cache = DataSet::init(Value::array(static::$cache));
    }

    public function __toString(): string
    {
        return serialize($this);
    }
}
