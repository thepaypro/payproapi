<?php
namespace AppBundle\Service\PayPro;

use Doctrine\ORM\EntityManager;

use AppBundle\Entity\Account;

/**
 * Class AccountManager
 * @package AppBundle\Service
 */
class AccountManager
{
    protected $em;
    protected $contisSoapClient;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em, String $contisWsdlUrl)
    {
        $this->em = $em;
        $this->contisSoapClient = new SoapClient($contisWsdlUrl);
    }

    /**
     * This method will create the cardHolder on Contis system and will persist the new account of the user.
     * @param  Account $account
     * @return something to reflect if something goes ok or not
     */
    public function createAccount(
        String $forename,
        String $lastname,
        String $birthDate,
        String $documentType,
        String $documentNumber,
        Agreement $agreement,
        String $principalAddress,
        String $postcode,
        String $city,
        Country $country,
    )
    {
        
        $account = new Account();
        die();
    }
}
