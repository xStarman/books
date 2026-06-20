<?php

namespace App\Exceptions;

use Exception;

class BookAlreadyExistsException extends Exception
{
    public function __construct($message = "book_already_exists", $code = 409, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
