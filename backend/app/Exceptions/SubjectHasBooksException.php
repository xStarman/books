<?php

namespace App\Exceptions;

use Exception;

class SubjectHasBooksException extends BooksException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("subject_has_books", 409, $previous);
    }
}
