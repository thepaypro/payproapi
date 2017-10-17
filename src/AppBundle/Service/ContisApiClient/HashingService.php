<?php

namespace AppBundle\Service\ContisApiClient;

use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class HashingService
 * @package AppBundle\Service\ContisApiClient
 */
class HashingService
{
    const CONTIS_TOKEN_KEY = 'payproapi.contis_authentication_token';
    const CONTIS_SECURITY_KEY = 'payproapi.contis_authentication_security_key';
    const TOKEN_EXPIRY_DATE_KEY = 'payproapi.contis_token_expiry_time';

    protected $session;
    protected $pin_iv_key;
    protected $pin_secret_key;

    /**
     * @param Session $session
     * @param String $pin_iv_key
     * @param String $pin_secret_key
     */
    public function __construct(Session $session, String $pin_iv_key, String $pin_secret_key) {
        $this->session = $session;
        $this->pin_iv_key = $pin_iv_key;
        $this->pin_secret_key = $pin_secret_key;
    }

    /**
     * Generates the HashDataString and Hash for the specified array.
     * @param  array  $params
     * @return array
     */
    public function generateHashDataStringAndHash(Array $params)
    {
        $hashDataString = '';
        foreach ($params as $key => $param) {
            $combineOperator = $param === end($params) ? '' : '&';
            $stringParam = $param === 0 ? '' : $param;
            $hashDataString = $hashDataString.$stringParam.$combineOperator;
        }

        $params['HashDataString'] = $hashDataString;
        $securityKey = $this->session->has(self::CONTIS_SECURITY_KEY) ? $this->session->get(self::CONTIS_SECURITY_KEY) : '';
        $params['Hash'] = md5(mb_convert_encoding($hashDataString.$securityKey, "UCS-2LE", "JIS, eucjp-win, sjis-win"));

        return $params;
    }

    public function pinDecrypt(String $pin){
        dump($this->pin_iv_key);die();
        $decPin = openssl_decrypt( base64_decode($pin), "des3", hex2bin($this->pin_secret_key), 0, hex2bin($this->pin_iv_key));
        return $decPin;
    }
}
