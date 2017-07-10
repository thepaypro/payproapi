<?php

namespace AppBundle\Service\Contis;

use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;
use DateTime;
use Exception;

/**
 * Class AuthenticationService
 * @package AppBundle\Service\Contis
 */
class AuthenticationService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const CONTIS_SECURITY_KEY = 'payproapi.contis_authentication_security_key';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';

    protected $session;
    protected $requestService;
    protected $hashingService;
    protected $contisApiHost;
    protected $contisUsername;
    protected $contisPassword;

    /**
     * @param String $session
     * @param String $contisApiHost
     * @param String $contisUsername
     * @param String $contisPassword
     */
    public function __construct(
        Session $session,
        RequestService $requestService,
        HashingService $hashingService,
        String $contisApiHost,
        String $contisUsername,
        String $contisPassword
    ) {
        $this->session = $session;
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->contisApiHost = $contisApiHost;
        $this->contisUsername = $contisUsername;
        $this->contisPassword = $contisPassword;
        $this->httpClient = new Client();
    }

    /**
     * Return the token in session and if it's expired refresh it.
     * @return String $token
     */
    public function getAuthenticationToken() : String
    {
        if (!$this->session->has(self::CONTIS_TOKEN_KEY) || !$this->session->has(self::TOKEN_EXPIRY_DATE_KEY)) {
            return $this->authenticate();
        }

        $token = $this->session->get(self::CONTIS_TOKEN_KEY);
        $expiryDate = $this->session->get(self::TOKEN_EXPIRY_DATE_KEY);
        $now = strtotime('now');

        if ($expiryDate-$now < 60*30) {
            return $this->authenticate();
        }

        return $token;
    }

    /**
     * Authenticates with Contis
     * @return Return String
     */
    private function authenticate() : String
    {
        $params = $this->hashingService->generateHashDataStringAndHash([
            'UserName' => $this->contisUsername,
            'Password' => $this->contisPassword
        ]);

        $response = $this->requestService->call('Login', $params);

        $token = $response['LoginResult']['Token'];
        $securityKey = $response['LoginResult']['SecurityKey'];
        $expiryDate = trim($response['LoginResult']['SessionExpiryDateTime'], '/Date()')/1000;

        $this->session->set(self::CONTIS_TOKEN_KEY, $token);
        $this->session->set(self::CONTIS_SECURITY_KEY, $securityKey);
        $this->session->set(self::TOKEN_EXPIRY_DATE_KEY, $expiryDate);

        return $token;
    }
}
