<?php
namespace AppBundle\Service\PayPro;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use AppBundle\Entity\Account;
use AppBundle\Repository\CountryRepository;
use AppBundle\Repository\AgreementRepository;
use \SoapClient;

/**
 * Class AccountManager
 * @package AppBundle\Service
 */
class AccountManager
{
    protected $agreementRepository;
    protected $countryRepository;
    protected $validationService;
    protected $contisSoapClient;

    /**
     * @param EntityManager $em
     */
    public function __construct(
        AgreementRepository $agreementRepository,
        CountryRepository $countryRepository,
        ValidatorInterface $validationService,
        String $contisWsdlUrl
    )
    {
        $this->agreementRepository = $agreementRepository;
        $this->countryRepository = $countryRepository;
        $this->validationService = $validationService;
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
        Int $agreementId,
        String $principalAddress,
        String $secondaryAddress,
        String $postcode,
        String $city,
        Int $countryId
    )
    {
        $agreement = $this->agreementRepository->findOneById($agreementId);
        $country = $this->countryRepository->findOneById($countryId);

        $account = new Account(
            $forename,
            $lastname,
            $birthDate,
            $documentType,
            $documentNumber,
            $agreement,
            $principalAddress,
            $secondaryAddress,
            $postcode,
            $city,
            $country
        );

        $errors = $this->validationService->validate($account);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new BadRequestHttpException($error->getMessage());
            }
        }

        $params = [
            'UserName' => 'Payprobeta',
            'Password' => 'P@yprobeta',
        ];
        $hashDataString = '';
        foreach ($params as $key => $param) {
            $hashDataString = $hashDataString.'&'.$param;
        }
        $hashDataString = ltrim($hashDataString, '&');
        $params['HashDataString'] = $hashDataString;
        $params['Hash'] = md5($hashDataString);
        // $params = [
        //     'FirstName' => $account->getForename(),
        //     'LastName' => $account->getLastname(),
        //     'Gender' => 'N',
        //     'DOB' => $account->getBirthdate(),
        //     'Street' => $account->getPrincipalAddress(),
        //     'City' => $account->getCity(),
        //     'Postcode' => $account->getPostcode(),
        //     'Country' => $account->getCountry()->getIso3(),
        //     'IsMain' => 1,
        //     'Relationship' => 'self'
        // ];

        // dump($params);
        // die();
        $response = $this->contisSoapClient->__soapCall('Login', $params);

        dump($response);
        die();
        return;
    }
}
