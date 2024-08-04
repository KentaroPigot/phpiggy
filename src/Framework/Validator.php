<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidationException;

class Validator
{
    // Rule = class qui défini comment valider un element en particulier
    private $rules = [];

    public function add(string $alias, RuleInterface $rule)
    {
        $this->rules[$alias] = $rule;
    }

    public function validate(array $formData, array $fields)
    {
        $errors = [];

        // Je récupère tous les fields que le mec veut valider
        // Pour chaque field à valider je récupère le nom et les règles que l'on veut lui appliquer
        foreach ($fields as $fieldName => $rules) {

            // Pour chaque règle, je récupère l'instance de celle ci dans mes $rules et je l'utilise pour valider le field actuel
            foreach ($rules as $rule) {
                $ruleParams = [];

                if (str_contains($rule, ':')) {
                    [$rule, $ruleParams] = explode(':', $rule);    //Pareil que split! 
                    $ruleParams = explode(',', $ruleParams);
                }

                $ruleValidator = $this->rules[$rule];

                if ($ruleValidator->validate($formData, $fieldName, $ruleParams)) {
                    continue;
                }

                $errors[$fieldName][] = $ruleValidator->getMessage($formData, $fieldName, $ruleParams);
            }
        }

        if (count($errors)) {
            throw new ValidationException($errors);
        }
    }
}
