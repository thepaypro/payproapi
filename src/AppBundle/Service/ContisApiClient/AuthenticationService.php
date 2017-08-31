<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Component\HttpFoundation\Session\Session;
use Exception;

/**
 * Class AuthenticationService
 * @package AppBundle\Service\ContisApiClient
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
     * @param string $session
     * @param string $contisApiHost
     * @param string $contisUsername
     * @param string $contisPassword
     */
    public function __construct(
        Session $session,
        RequestService $requestService,
        HashingService $hashingService,
        string $contisApiHost,
        string $contisUsername,
        string $contisPassword
    ) {
        $this->session = $session;
        $this->requestService = $requestService;
        $this->hashingService = $hashingService;
        $this->contisApiHost = $contisApiHost;
        $this->contisUsername = $contisUsername;
        $this->contisPassword = $contisPassword;
    }

    /**
     * Return the token in session and if it's expired refresh it.
     * @return string $token
     */
    public function getAuthenticationToken() : string
    {
        if (!$this->session->has(self::CONTIS_TOKEN_KEY) || !$this->session->has(self::TOKEN_EXPIRY_DATE_KEY)) {
            return $this->authenticate();
        }

        $token = $this->session->get(self::CONTIS_TOKEN_KEY);
        $expiryDate = $this->session->get(self::TOKEN_EXPIRY_DATE_KEY);
        $now = strtotime('now');

        if ($expiryDate-$now < 30*60) {
            return $this->authenticate();
        }

        return $token;
    }

    /**
     * Authenticates with Contis
     * @return Return string
     */
    private function authenticate() : string
    {
        $params = $this->hashingService->generateHashDataStringAndHash([
            'UserName' => $this->contisUsername,
            'Password' => $this->contisPassword
        ]);

        $response = $this->requestService->call('Login', $params);

        $token = $response['LoginResult']['Token'];
        $securityKey = $response['LoginResult']['SecurityKey'];
        $expiryDate = (trim($response['LoginResult']['SessionExpiryDateTime'], '/Date()')/1000);

        $this->session->set(self::CONTIS_TOKEN_KEY, $token);
        $this->session->set(self::CONTIS_SECURITY_KEY, $securityKey);
        $this->session->set(self::TOKEN_EXPIRY_DATE_KEY, $expiryDate);

        return $token;
    }
}
