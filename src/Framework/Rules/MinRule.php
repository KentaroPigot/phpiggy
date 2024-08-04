<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException; // Erreur pour "invalid or insufficient arguments for a function

class MinRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        if (empty($params[0])) {
            throw new InvalidArgumentException("Minimum length not specified");    // On a pas reçu un argument, le param.
        }

        $length = (int) $params[0];
        return $data[$field] >= $length;
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "Must be at least {$params[0]}";
    }
}
