<?php

namespace Samek\Validator\Validators;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * ArgsValidator
 *
 * This class validates all url arguments.
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package LibSamek\Validator\Validators
 */
class ArgsValidator
{

    /**
     * Assoc. array of url arguments.
     *
     * @var array
     */
    private $args;

    /**
     * ArgsValidator constructor.
     *
     * @param array $args Assoc. array of arguments
     */
    public function __construct($args)
    {
        $this->args = $args;

        CustomValidationInitializer::initValidator();
    }

    /**
     * This method does validation of arguments.
     *
     * @return bool
     * @throws NestedValidationException
     */
    public function validate()
    {
        v::key("USER_ID", v::uuid())->assert($this->args);

        return true;
    }
}
