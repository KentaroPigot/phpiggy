<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\SessionException;
use Framework\Contracts\MiddlewareInterface;

class SessionMiddleware implements MiddlewareInterface
{
    public function process(callable $next)
    {
        // On ne veut pas de deux sessions à la fois. Si dans mon composer j'ai un package qui a déjà démarré une session, on lance une erreur.
        if (session_status() === PHP_SESSION_ACTIVE) {
            throw new SessionException("Session already active.");
        }

        // On ne peut pas démarrer une session alors que des data ont déjà été send pour cette requete car la première chose envoyé dès la connexion est le header qui détient le PHPSESSID et les cookies etc..
        // Quand n'importe quel element du body est envoyé, le header est déjà "fermé" et ne peut plus être modifié.
        if (headers_sent($filename, $line)) {
            throw new SessionException("Headers already sent. Consider enabling output buffering. Data outputted from {$filename} - Line: {$line}");
        }

        session_set_cookie_params(
            [
                'secure' => $_ENV['APP_ENV'] === 'production',
                'httponly' => true,
                'samesite' => 'lax'
            ]
        );

        session_start();

        $next();

        session_write_close();
    }
}
