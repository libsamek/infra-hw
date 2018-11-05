<?php

namespace Samek\Validator;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Respect\Validation\Exceptions\NestedValidationException;
use Samek\Validator\Helpers\Config;
use Samek\Validator\Helpers\ResponseHelper;
use Samek\Validator\Service\UploadService;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App;


$app->post('/user/{USER_ID}/data', function (Request $request, Response $response, array $args) {
    try
    {
        $argsValidator = new Validators\ArgsValidator($args);
        $argsValidator->validate();

        $bodyValidator = new Validators\BodyValidator($request->getBody());
        $bodyValidator->validate();
    }
    catch (NestedValidationException $exc)
    {
        return ResponseHelper::handleValidationException($exc, $response);
    }

    // call next service
    $request->getBody()->rewind();
    $body = json_decode($request->getBody()->getContents(), true);
    $url = Config::getSettingValue("API_URL");
    $service = new UploadService($url, $body, $response);

    $response = $service->upload();

    return $response;
});

$app->run();
