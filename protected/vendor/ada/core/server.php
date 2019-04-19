<?php

namespace Ada\Core;

class Server extends Input
{
    public function __construct(array $data = [])
    {
        $this->sets($data ?: array_change_key_case($_SERVER));
    }
}
