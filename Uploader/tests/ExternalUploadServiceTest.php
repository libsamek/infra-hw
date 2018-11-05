<?php

use Samek\Uploader;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class ExternalUploadServiceTest extends TestCase
{
    const TIMEOUT = 5;

    private $data = [
        "accountId" => "85010c53-eafd-4ce2-93c0-5818b593c25b",
        "timestamp" => 1540462747,
        "data" => "random string that can be valid JSON too",
        "isActive" => true,
        "balance" => "$1,892.83",
        "picture" => "http://placehold.it/32x32",
        "age" => 26,
        "eyeColor" => "blue",
        "name" => [
            "first" => "Katina",
            "last" => "Phelps"
        ]
    ];

    public function testValidResponse()
    {
        $responseBody = '{ "message": "Succesfully uploaded data"}';
        $jsonBody = json_decode($responseBody, true);

        $mock = new MockHandler([
            new Response(200, ["Content-Type" => "application/json;charset=utf-8"], $responseBody),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $response = new \Slim\Http\Response();

        $uploadService = new Uploader\Service\ExternalUploadService($client, self::TIMEOUT, $response);

        $response = $uploadService->upload($this->data);
        $response->getBody()->rewind();

        $plain = $response->getBody()->getContents();

        $body = json_decode($plain, true);

        $this->assertEquals($jsonBody['message'], $body['message']);

        $this->assertEquals($response->getStatusCode(), 200);
    }

    public function testTeaPotResponse()
    {
        $responseBody = '{ "message": "I am a teapot"}';
        $jsonBody = json_decode($responseBody, true);

        $mock = new MockHandler([
            new Response(418, ["Content-Type" => "application/json;charset=utf-8"], $responseBody),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $timeout = 5;
        $response = new \Slim\Http\Response();

        $uploadService = new Uploader\Service\ExternalUploadService($client, $timeout, $response);

        $response = $uploadService->upload($this->data);
        $response->getBody()->rewind();

        $plain = $response->getBody()->getContents();

        $body = json_decode($plain, true);

        $this->assertEquals($jsonBody['message'], $body['message']);

        $this->assertEquals($response->getStatusCode(), 400);
    }
}
