<?php

namespace App\Exceptions;

use Exception;

class AuthorAlreadyExistsException extends BooksException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("author_already_exists", 409, $previous);
    }
}
