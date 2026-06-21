<?php

namespace App\Exceptions;

use Exception;

class SubjectAlreadyExistsException extends BooksException
{
    public function __construct(Exception $previous = null)
    {
        parent::__construct("subject_already_exists", 409, $previous);
    }
}
