<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Accounts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 */
class Account implements \JsonSerializable
{
    const DOCUMENT_TYPE_DNI = "DNI";
    const DOCUMENT_TYPE_PASSPORT = "PASSPORT";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="account")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $forename;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $lastname;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     * @Assert\DateTime(format="d/m/Y")
     */
    protected $birthDate;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(callback = "getValidDocumentTypes")
     */
    protected $documentType;
   
    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $documentNumber;
    
    /**
     * @ORM\ManyToOne(targetEntity="Agreement", inversedBy="accounts", cascade={"all"})
     * @ORM\JoinColumn(name="agreement_id", referencedColumnName="id", nullable=false)
     */
    protected $agreement;

    /**
     * @ORM\Column(type="string")
     */
    protected $cardHolderId;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="payer")
     */
    protected $sentTransactions;

    /**
     * @ORM\OneToMany(targetEntity="Transaction", mappedBy="beneficiary")
     */
    protected $receivedTransactions;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $principalAddress;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $secondaryAddress;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $postcode;
    
    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="accounts", cascade={"all"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     *
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     *
     */
    protected $updatedAt;

    public function __construct(
        String $forename,
        String $lastname,
        String $birthDate,
        String $documentType,
        String $documentNumber,
        Agreement $agreement,
        String $principalAddress,
        String $secondaryAddress,
        String $postcode,
        String $city,
        Country $country
    )
    {
        $this->forename = $forename;
        $this->lastname = $lastname;
        $this->birthDate = $birthDate;
        $this->documentType = $documentType;
        $this->documentNumber = $documentNumber;
        $this->agreement = $agreement;
        $this->principalAddress = $principalAddress;
        $this->secondaryAddress = $secondaryAddress;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->country = $country;
    }

    public function jsonSerialize()
    {
        $allProperties = get_object_vars($this);
        $allProperties['user'] = $this->user->getId();
        return $allProperties;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set forename
     *
     * @param string $forename
     * @return Account
     */
    public function setForename($forename)
    {
        $this->forename = $forename;

        return $this;
    }

    /**
     * Get forename
     *
     * @return string
     */
    public function getForename()
    {
        return $this->forename;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Account
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return Account
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set documentType
     *
     * @param string $documentType
     * @return Account
     */
    public function setDocumentType($documentType)
    {
        $this->documentType = $documentType;

        return $this;
    }

    /**
     * Get documentType
     *
     * @return string
     */
    public function getDocumentType()
    {
        return $this->documentType;
    }

    /**
     * Set documentNumber
     *
     * @param string $documentNumber
     * @return Account
     */
    public function setDocumentNumber($documentNumber)
    {
        $this->documentNumber = $documentNumber;

        return $this;
    }

    /**
     * Get documentNumber
     *
     * @return string
     */
    public function getDocumentNumber()
    {
        return $this->documentNumber;
    }

    /**
     * Set accountTypeId
     *
     * @param string $accountTypeId
     * @return Account
     */
    public function setAccountTypeId($accountTypeId)
    {
        $this->accountTypeId = $accountTypeId;

        return $this;
    }

    /**
     * Get accountTypeId
     *
     * @return string
     */
    public function getAccountTypeId()
    {
        return $this->accountTypeId;
    }

    /**
     * Set cardHolderId
     *
     * @param string $cardHolderId
     * @return Account
     */
    public function setCardHolderId($cardHolderId)
    {
        $this->cardHolderId = $cardHolderId;

        return $this;
    }

    /**
     * Get cardHolderId
     *
     * @return string
     */
    public function getCardHolderId()
    {
        return $this->cardHolderId;
    }

    /**
     * Set principalAddress
     *
     * @param string $principalAddress
     * @return Account
     */
    public function setPrincipalAddress($principalAddress)
    {
        $this->principalAddress = $principalAddress;

        return $this;
    }

    /**
     * Get principalAddress
     *
     * @return string
     */
    public function getPrincipalAddress()
    {
        return $this->principalAddress;
    }

    /**
     * Set secondaryAddress
     *
     * @param string $secondaryAddress
     * @return Account
     */
    public function setSecondaryAddress($secondaryAddress)
    {
        $this->secondaryAddress = $secondaryAddress;

        return $this;
    }

    /**
     * Get secondaryAddress
     *
     * @return string
     */
    public function getSecondaryAddress()
    {
        return $this->secondaryAddress;
    }

    /**
     * Set postcode
     *
     * @param string $postcode
     * @return Account
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;

        return $this;
    }

    /**
     * Get postcode
     *
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Account
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param Country $country
     * @return Account
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

   /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = new \DateTime("now");
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Gets triggered every time on update
     *
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        $this->updatedAt = new \DateTime("now");
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Array
     */
    public function getValidDocumentTypes() : Array
    {
        $constants = self::getConstants();
        $key_types =  array_filter(array_flip($constants), function ($k) {
            return (bool)preg_match('/DOCUMENT_TYPE/', $k);
        });

        $document_types = array_intersect_key($constants, array_flip($key_types));
        return $document_types;
    }

    public static function getConstants()
    {
        $clientClass = new \ReflectionClass(__CLASS__);
        return $clientClass->getConstants();
    }
}
