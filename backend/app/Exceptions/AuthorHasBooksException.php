<?php

namespace App\Exceptions;

use Exception;

class AuthorHasBooksException extends BooksException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("author_has_books", 409, $previous);
    }
}
