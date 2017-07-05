<?php

namespace AppBundle\Service\Contis;

use Symfony\Component\HttpFoundation\Session\Session;
use GuzzleHttp\Client;
use DateTime;
use Exception;

/**
 * Class RequestService
 * @package AppBundle\Service
 */
class RequestService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';
    protected $contisApiHost;
    protected $contisSecretKey;
    protected $session;

    /**
     * @param String $contisSecretKey
     * @param String $contisApiHost
     */
    public function __construct(
        Session $session,
        String $contisApiHost,
        String $contisSecretKey,
        String $contisUsername,
        String $contisPassword
    ) {
        $this->session = $session;
        $this->contisApiHost = $contisApiHost;
        $this->contisSecretKey = $contisSecretKey;
        $this->contisUsername= $contisUsername;
        $this->contisPassword = $contisPassword;
        $this->httpClient = new Client();
    }

    /**
     * This method authenticates with Contis if needed and create the hash data string and hash required by contis to execute the call.
     * @return Array
     */
    public function call(String $endpoint, Array $params) : Array
    {
        $params = $this->generateHashDataStringAndHash($params);
        $params = ['CardHolderSearchInfo' => $params];
        $params['token'] = $this->getAuthenticationToken();
        // dump($params);die();
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->contisApiHost.$endpoint,
                ['json' => $params]
            );
        } catch (Exception $e) {
            dump($e->getResponse()->getBody()->getContents());die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private function generateHashDataStringAndHash(Array $params)
    {
        $hashDataString = '';
        foreach ($params as $key => $param) {
            $hashDataString = $hashDataString.'&'.$param;
        }

        $hashDataString = ltrim($hashDataString, '&');
        $params['HashDataString'] = $hashDataString;
        $params['Hash'] = md5(mb_convert_encoding($hashDataString.$this->contisSecretKey, "UCS-2LE", "JIS, eucjp-win, sjis-win"));

        return $params;
    }

    private function getAuthenticationToken() : String
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

    private function authenticate() : String
    {
        $params = $this->generateHashDataStringAndHash([
            'UserName' => $this->contisUsername,
            'Password' => $this->contisPassword
        ]);

        $params = ['objInfo' => $params];

        $response = $this->httpClient->request(
            'POST',
            $this->contisApiHost.'Login',
            ['json' => $params]
        );   

        $response = json_decode($response->getBody()->getContents(), true);

        $token = $response['LoginResult']['Token'];
        $expiryDate = trim($response['LoginResult']['SessionExpiryDateTime'], '/Date()')/1000;

        $this->session->set(self::CONTIS_TOKEN_KEY, $token);
        $this->session->set(self::TOKEN_EXPIRY_DATE_KEY, $expiryDate);

        return $token;
    }
}
