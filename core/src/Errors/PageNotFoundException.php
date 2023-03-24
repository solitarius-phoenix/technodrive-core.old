<?php

namespace Technodrive\Core\Errors;

class PageNotFoundException extends \Exception
{
    public function __construct(string $message = "", int $code = 404, ?string $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}