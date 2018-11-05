<?php

namespace Samek\Uploader\Service;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use \Psr\Http\Message\ResponseInterface as Response;

/**
 * Class ExternalUploadService.
 *
 * @author Samo Jelovsek <samo@jelovsek.net>
 * @package Samek\Uploader\Service
 */
class ExternalUploadService
{
    /**
     * @var string Upload URI
     */
    const UPLOAD_URI = "/api/upload";

    /**
     * @var Client Instance of Client
     */
    private $client;

    /**
     * @var int Timeout in seconds
     */
    private $timeout;

    /**
     * @var Response Response for our service
     */
    private $response;

    /**
     * ExternalUploadService constructor.
     *
     * @param Client $client Instance of Client
     * @param int $timeout Timeout in seconds
     * @param Response $response Response for our service
     */
    public function __construct(Client $client, int $timeout, Response $response)
    {
        $this->client = $client;

        $this->timeout = $timeout;

        $this->response = $response;
    }

    /**
     * Helper function for generating error message.
     *
     * @return array Assoc. array to be used with json_encode
     */
    private function getInvalidBodyResponse()
    {
        return [ "error" => "received invalid response body (not valid json) from external service" ];
    }

    /**
     * External service upload action
     *
     * @param array $body Assoc. array to be used as body
     * @return Response Response for our service
     */
    public function upload($body)
    {
        try {

            $response = $this->client->post(self::UPLOAD_URI, [
                //'debug' => fopen('php://stderr', 'w'),
                "connect_timeout" => $this->timeout,
                "timeout" => $this->timeout,
                "json" => $body]);

            $status = $response->getStatusCode();
            $extBody = json_decode($response->getBody()->getContents(), true);
            if($extBody == null)
            {
                $extBody = $this->getInvalidBodyResponse();
            }

            $this->response = $this->response->withStatus($status)->withJson($extBody);

            return $this->response;
        }
        catch (RequestException $exc)
        {
            if ($exc->hasResponse())
            {
                $this->response = $this->response->withStatus($exc->getCode());

                $b = json_decode($exc->getResponse()->getBody()->getContents(), true);
                if ($b == null)
                {
                    $b = $this->getInvalidBodyResponse();
                }

                $this->response = $this->response->withJson($b);

                return $this->response;
            }
            else
            {
                $this->response = $this->response->withStatus(500)->withJson(["net-error" => $exc->getMessage()]);
            }
        }

        return $this->response;
    }
}
