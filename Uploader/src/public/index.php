<?php

namespace Samek\Uploader;

use GuzzleHttp\Client;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Samek\Uploader\Helpers\Config;
use Samek\Uploader\Service\ExternalUploadService;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \Slim\App;

$app->post('/api/upload', function (Request $request, Response $response) {
    $url = Config::getSettingValue("API_URL");
    $timeout = Config::getSettingValue("TIMEOUT");
    $client = new Client([ "base_uri" => $url ]);

    $uploader = new ExternalUploadService($client, $timeout, $response);

    $response = $uploader->upload($request->getBody()->getContents());

    return $response;
});

$app->run();
