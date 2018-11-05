<?php

declare(strict_types=1);

namespace Samek\Validator\Validators;

use Respect\Validation\Exceptions\ValidationException;

/**
 * Class UuidException
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Validator\Validators
 */
class UuidException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => '{{name}} is not a valid UUID v4',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => '{{name}} is a valid UUID v4',
        ],
    ];
}
