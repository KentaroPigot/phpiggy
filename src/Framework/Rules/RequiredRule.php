<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class RequiredRule implements RuleInterface
{
    public function validate(array $data, string $field, array $params): bool
    {
        // On check voir si un field en particulier est empty
        return !empty($data[$field]);
    }

    public function getMessage(array $data, string $field, array $params): string
    {
        return "This field is required.";
    }
}
