<?php
namespace AppBundle\Service\Contis;

use GuzzleHttp\Client;
// use Exception;
/**
 * Class RequestService
 * @package AppBundle\Service
 */
class RequestService
{
    protected $contisApiHost;
    protected $contisSecretKey;

    /**
     * @param String $contisSecretKey
     * @param String $contisApiHost
     */
    public function __construct(
        String $contisSecretKey,
        String $contisApiHost
    ) {
        $this->contisSecretKey = $contisSecretKey;
        $this->contisApiHost = $contisApiHost;
        $this->httpClient = new Client();
    }

    /**
     * This method create the hash data string and hash required by contis and execute the call.
     * @return Array
     */
    public function call(String $endpoint, Array $params) : Array
    {
        $hashDataString = '';
        foreach ($params as $key => $param) {
            $hashDataString = $hashDataString.'&'.$param;
        }

        $hashDataString = ltrim($hashDataString, '&');
        $params['HashDataString'] = $hashDataString;
        $params['Hash'] = md5(mb_convert_encoding($hashDataString.$this->contisSecretKey, "UCS-2LE", "JIS, eucjp-win, sjis-win"));
        try {
            $response = $this->httpClient->request(
                'POST',
                $this->contisApiHost.$endpoint,
                ['json' => $params]
            );   
        } catch (Exception $e) {
            dump($e);
            die();
        }

        return json_decode($response->getBody()->getContents(), true);
    }
}
