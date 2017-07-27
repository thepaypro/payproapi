<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;
use DateTime;
use Exception;

/**
 * Class RequestService
 * @package AppBundle\Service\ContisApiClient
 */
class RequestService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const CONTIS_SECURITY_KEY = 'payproapi.contis_authentication_security_key';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';

    protected $contisApiHost;
    protected $contisSecretKey;
    protected $session;

    /**
     * @param string $contisSecretKey
     * @param string $contisApiHost
     */
    public function __construct(string $contisApiHost) {
        $this->contisApiHost = $contisApiHost;
        $this->httpClient = new Client();
    }

    /**
     * This method authenticates with Contis if needed and create the hash data string and hash required by contis to execute the call.
     * @param string $endpoint
     * @param array $params
     * @param array $requestParams
     * @param string $jsonParametersKey
     * @return array
     */
    public function call(string $endpoint, array $params, array $requestParams = [], string $jsonParamtersKey = 'objInfo') : array
    {
        $payload['objReqInfo'] = $requestParams;
        $payload[$jsonParamtersKey] = $params;

        $payload = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if ($endpoint != 'Login') {
            // dump($payload);die();
        }
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->contisApiHost.$endpoint,
                [
                    'headers' => [
                        'Content-type' => 'application/json'
                    ],
                    'body' => $payload
                ]
            );
        } catch (Exception $e) {
            dump($e->getResponse()->getBody()->getContents());die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
