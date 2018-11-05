<?php

namespace Samek\Validator\Validators;

use Respect\Validation\Validator as v;

/**
 * Class CustomValidationInitializer
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Validator\Validators
 */
class CustomValidationInitializer
{
    /**
     * Static method that initializes Respect/Validation custom rules.
     *
     * @return void
     */
    public static function initValidator()
    {
        v::with("Samek\\Validator\\Validators\\");
    }
}
