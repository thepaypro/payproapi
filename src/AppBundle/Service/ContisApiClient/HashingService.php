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

    public function pinDecrypt(String $pin)
    {
        $decPin = $this->decrypt3DES($this->pin_secret_key, $this->pin_iv_key, $pin);
        
        return $decPin;
    }

    public function decrypt3DES($key64,$iv64,$encText)
    {      
      $keybytes = pack('H*',$key64);
      $keybytes .= substr($keybytes,0,8);
      $ivbytes = pack('H*',$iv64);
      
      $decryptbytes = base64_decode($encText);
      $decryptRaw = mcrypt_decrypt(MCRYPT_3DES, $keybytes, $decryptbytes, MCRYPT_MODE_CBC, $ivbytes);

      return $decryptRaw;
    }
}
