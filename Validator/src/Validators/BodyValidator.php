<?php

namespace Samek\Validator\Validators;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;
use Psr\Http\Message\StreamInterface;

/**
 * Class BodyValidator
 *
 * This class is responsible for validation of request body.
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Validator\Validators
 */
class BodyValidator
{
    /**
     * @var int Max body size in KB
     */
    const MAX_BODY_SIZE = 10 * 1000; // 10 KB


    /**
     * @var StreamInterface Request body
     */
    private $requestBody;

    /**
     * BodyValidator constructor.
     *
     * @param StreamInterface $body Request body
     */
    public function __construct(StreamInterface $body)
    {
        $this->requestBody = $body;

        CustomValidationInitializer::initValidator();
    }

    /**
     * Validates if request body size is in limit.
     *
     * @return void
     * @throws ValidationException
     */
    private function validateBodySize()
    {
        $regSize = $this->requestBody->getSize();

        $sizeValidator = v::intType()
            ->max(self::MAX_BODY_SIZE)
            ->setTemplate('Body size is limited to ' . self::MAX_BODY_SIZE . ' B, received {{name}} B')
            ->assert($regSize);
    }

    /**
     * Validates body content and required fields.
     *
     * @return void
     * @throws ValidationException
     */
    private function validateBodyContent()
    {
        $arrBody = json_decode($this->requestBody->getContents(), true);

        v::notEmpty()->setTemplate("Body must be not empty and must be a valid JSON")
            ->assert($arrBody);

        v::key("accountId", v::uuid())
            ->key("timestamp", v::intVal())
            ->key("data", v::stringType())
            ->assert($arrBody);
    }

    /**
     * Validates request size and body.
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate()
    {
        $this->validateBodySize();

        $this->validateBodyContent();

        return true;
    }
}
