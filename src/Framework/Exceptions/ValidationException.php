<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;

// RuntimeException c'est pour les erreurs qui arrivent pendant que le code run, et qui n'ont pas à être fixed, mais handled
// Par exemple l'oubli d'un point virgule doit être fixed. 
class ValidationException extends RuntimeException
{
    public function __construct(public array $errors, int $code = 422)
    {
        parent::__construct(code: $code);
    }
}
