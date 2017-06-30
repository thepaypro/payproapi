<?php
namespace AppBundle\Service\Contis;

/**
 * Class LoginService
 * @package AppBundle\Service
 */
class LoginService
{
    protected $contisUsername;
    protected $contisPassword;
    protected $requestService;

    /**
     * @param EntityManager $em
     */
    public function __construct(
        String $contisUsername,
        String $contisPassword,
        RequestService $requestService
    ) {
        $this->contisUsername= $contisUsername;
        $this->contisPassword = $contisPassword;
        $this->requestService = $requestService;
    }

    /**
     * This method will get Contis authentication token.
     * @return something to reflect if something goes ok or not
     */
    public function login()
    {
        $response = $this->requestService->call(
            'Login',
            [
                'Password' => $this->contisPassword,
                'UserName' => $this->contisUsername
            ]
        );

        dump($response);die();

        return;
    }
}
