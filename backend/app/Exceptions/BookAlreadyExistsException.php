<?php

namespace App\Exceptions;

use Exception;

class BookAlreadyExistsException extends BooksException
{
    public function __construct($message = "book_already_exists", Exception $previous = null)
    {
        parent::__construct($message, 409, $previous);
    }
}
