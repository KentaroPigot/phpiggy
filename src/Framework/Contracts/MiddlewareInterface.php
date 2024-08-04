<?php

declare(strict_types=1);

namespace Framework\Contracts;

interface MiddlewareInterface
{
    // Sert à process la request avant que le controller fasse le taff
    public function process(callable $next);
}
