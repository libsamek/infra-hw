<?php

namespace Samek\Validator\Validators;

use Respect\Validation\Rules\AbstractRule;

/**
 * Uuid
 *
 * Additional rule class for respect/validation for validation of UUID v4.
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Validator\Validators
 */
class Uuid extends AbstractRule
{
    /**
     * Regular expression for checking UUIDs v4.
     */
    private const REG_EX = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    /**
     * Test whether input is UUID v4.
     *
     * @param mixed $input test value
     *
     * @return bool
     */
    public function validate($input)
    {
        if (!is_string($input)) {
            return false;
        }

        return preg_match(self::REG_EX, $input) > 0;
    }
}
