<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {

        // Comme le controller est un nested callback (dans le next du next)
        // On peut faire un try catch dessus et ça remontera jusque là !
        try {
            $next();
        } catch (ValidationException $e) {
            $oldFormData = $_POST;

            $excludedFields = ['password', 'confirmPassword'];
            $formattedFormData = array_diff_key(
                $oldFormData,
                array_flip($excludedFields)
            );

            $_SESSION["errors"] = $e->errors;
            $_SESSION["oldFormData"] = $formattedFormData;

            // $referer = $_SERVER["HTTP_REFERER"]; // C'est une valeur disponible après l'envoi d'un formulaire. il store l'url où le formulaire a été envoyé 
            // redirectTo($referer);

            $returnTo = $_SESSION['return_to'] ?? '/';
            redirectTo($returnTo);
        }
    }
}
