<?php

namespace App\Exceptions;

use Exception;

class BooksException extends Exception
{
    public function __construct($message = "Erro na aplicação de livros", $code = 400, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
