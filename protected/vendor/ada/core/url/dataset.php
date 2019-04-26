<?php

namespace Ada\Core\Url;

class DataSet extends \Ada\Core\DataSet
{
    public function set(string $key, $value): void
    {
        $this->data[\Ada\Core\Url::clean($key)] = \Ada\Core\Url::clean($value);
    }
}
