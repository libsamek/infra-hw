<?php

namespace Samek\Validator\Service;

use GuzzleHttp\Exception\RequestException;
use \Psr\Http\Message\ResponseInterface as Response;
use GuzzleHttp\Client;

/**
 * Class UploadService
 *
 * Class handles all communication with external service
 * and prepares response for our service.
 *
 * @author Samo Jelovšek <samo@jelovsek.net>
 * @package Samek\Validator\Service
 */
class UploadService
{
    /**
     * @var string Upload url constant
     */
    const UPLOAD_URI = "/api/upload";

    /**
     * @var string Base URI
     */
    private $uri;

    /**
     * @var array Request body from API
     */
    private $body;

    /**
     * @var Response Response for API
     */
    private $response;

    /**
     * @var Client Guzzle client
     */
    private $client;

    /**
     * UploadService constructor.
     *
     * @param string $uri Base URI
     * @param array $body Assoc. array for body of request
     * @param Response $response Response for API
     */
    public function __construct(string $uri, array $body, Response $response)
    {
        $this->uri = $uri;
        $this->body = $body;
        $this->response = $response;

        $this->client = new Client([ 'base_uri' => $uri ]);
    }

    /**
     * Do upload to the external service.
     *
     * @return Response
     */
    public function upload()
    {
        try
        {
            $response = $this->client->post(self::UPLOAD_URI, ["json" => $this->body]);


            $status = $response->getStatusCode();
            // TODO: check?
            $body = json_decode($response->getBody()->getContents(), true);


            $this->response = $this->response->withStatus($status)->withJson($body);

        }
        catch (RequestException $exc)
        {
            if ($exc->hasResponse())
            {
                $this->response = $this->response->withStatus($exc->getCode());

                $b = json_decode($exc->getResponse()->getBody()->getContents(), true);
                if ($b != null) {
                    $this->response = $this->response->withJson($b);
                }
            }
            else
            {
                $this->response = $this->response->withStatus(500)->withJson(["message" => $exc->getMessage()]);
            }
        }

        return $this->response;
    }
}
