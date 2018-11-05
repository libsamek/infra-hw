<?php

namespace Samek\Validator\Helpers;

use Respect\Validation\Exceptions\NestedValidationException;
use \Psr\Http\Message\ResponseInterface as Response;
use Samek\Validator\Validators\BodyValidator;


/**
 * Class ResponseHelper
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Validator\Helpers
 */
class ResponseHelper
{
    /**
     * Adds validation exception messages in a response.
     *
     * @param NestedValidationException $exc Thrown exception
     * @param Response $response Response
     *
     * @return Response
     */
    public static function handleValidationException(NestedValidationException $exc, Response $response)
    {
        $message = [
            "messages" => $exc->getMessages()
        ];

        return $response->withJson($message, 400);
    }
}
