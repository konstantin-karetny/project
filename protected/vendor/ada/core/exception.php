<?php

namespace Ada\Core;

class Exception extends \Exception
{
    protected
        $context = '';

    public function __construct(
        string     $message  = '',
        int        $code     = 0,
        \Throwable $previous = null
    )
    {
        parent::__construct(
            $message,
            $code,
            $previous
        );
        $this->context = reset($this->getTrace())['class'];
        $this->message = $this->context . ' error. ' . $this->message;
    }

    public function getContext(): string
    {
        return $this->context;
    }
}
