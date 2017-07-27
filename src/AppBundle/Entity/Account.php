<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="Accounts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccountRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"documentNumber"})
 * @UniqueEntity(fields={"email"}, ignoreNull=true)
 */
class Account implements \JsonSerializable
{
    const DOCUMENT_TYPE_DNI = "DNI";
    const DOCUMENT_TYPE_PASSPORT = "PASSPORT";
    const DOCUMENT_TYPE_DRIVING_LICENSE = "DRIVING_LICENSE";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="account")
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity="Card", mappedBy="account")
     */
    protected $card;

    /**
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="account")
     */
    protected $profile;

    /**
     * @ORM\OneToOne(targetEntity="Notification", mappedBy="account")
     */
    protected $notification;

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
     * @ORM\Column(type="string", nullable=false)
     */
    protected $accountNumber;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $sortCode;

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
    protected $street;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $buildingNumber;

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
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Email()
     */
    protected $email;

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
        User $user,
        String $forename,
        String $lastname,
        DateTime $birthDate,
        String $documentType,
        String $documentNumber,
        Agreement $agreement,
        String $street,
        String $buildingNumber,
        String $postcode,
        String $city,
        Country $country
    )
    {
        $this->sentTransactions = new ArrayCollection();
        $this->receivedTransactions = new ArrayCollection();
        $this->users = new ArrayCollection();

        $this->users[] = $user;
        $this->forename = $forename;
        $this->lastname = $lastname;
        $this->birthDate = $birthDate;
        $this->documentType = $documentType;
        $this->documentNumber = $documentNumber;
        $this->agreement = $agreement;
        $this->street = $street;
        $this->buildingNumber = $buildingNumber;
        $this->postcode = $postcode;
        $this->city = $city;
        $this->country = $country;
    }

    public function jsonSerialize()
    {
        $publicProperties['users'] = $this->users->map(function ($user) {
            $user->getId();
        });
        $publicProperties['id'] = $this->id;
        $publicProperties['forename'] = $this->forename;
        $publicProperties['lastname'] = $this->lastname;
        $publicProperties['email'] = $this->email;
        $publicProperties['card'] = $this->card;
        $publicProperties['profile'] = $this->profile;
        $publicProperties['birthDate'] = $this->birthDate;
        $publicProperties['documentType'] = $this->documentType;
        $publicProperties['documentNumber'] = $this->documentNumber;
        $publicProperties['agreement'] = $this->agreement;
        $publicProperties['accountNumber'] = $this->accountNumber;
        $publicProperties['sortCode'] = $this->sortCode;
        $publicProperties['street'] = $this->street;
        $publicProperties['buildingNumber'] = $this->buildingNumber;
        $publicProperties['postcode'] = $this->postcode;
        $publicProperties['city'] = $this->city;
        $publicProperties['country'] = $this->country;
        $publicProperties['sentTransactions'] = $this->sentTransactions->map(function ($transaction) {
            $transaction->getId();
        });
        $publicProperties['receivedTransactions'] = $this->receivedTransactions->map(function ($transaction) {
            $transaction->getId();
        });
        $publicProperties['createdAt'] = $this->createdAt;
        $publicProperties['updatedAt'] = $this->updatedAt;

        return $publicProperties;
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
     * Get user
     *
     * @return User
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @param mixed $sentTransactions
     */
    public function setSentTransactions($sentTransactions)
    {
        $this->sentTransactions = $sentTransactions;
    }

    /**
     * @param mixed $receivedTransactions
     */
    public function setReceivedTransactions($receivedTransactions)
    {
        $this->receivedTransactions = $receivedTransactions;
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
    public function setBirthDate(DateTime $birthDate)
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
     * Set agreement
     *
     * @param string $agreement
     * @return Account
     */
    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;

        return $this;
    }

    /**
     * Get agreement
     *
     * @return string
     */
    public function getAgreement()
    {
        return $this->agreement;
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
     * Set accountNumber
     *
     * @param string $accountNumber
     * @return Account
     */
    public function setAccountNumber($accountNumber)
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->accountNumber;
    }

    /**
     * Set sortCode
     *
     * @param string $sortCode
     * @return Account
     */
    public function setSortCode($sortCode)
    {
        $this->sortCode = $sortCode;

        return $this;
    }

    /**
     * Get sortCode
     *
     * @return string
     */
    public function getSortCode()
    {
        return $this->sortCode;
    }

    /**
     * Set street
     *
     * @param string $street
     * @return Account
     */
    public function setStreet($street)
    {
        $this->street = $street;

        return $this;
    }

    /**
     * Get street
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Set buildingNumber
     *
     * @param string $buildingNumber
     * @return Account
     */
    public function setBuildingNumber($buildingNumber)
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    /**
     * Get buildingNumber
     *
     * @return string
     */
    public function getBuildingNumber()
    {
        return $this->buildingNumber;
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
        $this->updatedAt = new \DateTime("now");
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Account
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Account
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Account
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Add sentTransaction
     *
     * @param \AppBundle\Entity\Transaction $sentTransaction
     *
     * @return Account
     */
    public function addSentTransaction(\AppBundle\Entity\Transaction $sentTransaction)
    {
        $this->sentTransactions[] = $sentTransaction;

        return $this;
    }

    /**
     * Remove sentTransaction
     *
     * @param \AppBundle\Entity\Transaction $sentTransaction
     */
    public function removeSentTransaction(\AppBundle\Entity\Transaction $sentTransaction)
    {
        $this->sentTransactions->removeElement($sentTransaction);
    }

    /**
     * Get sentTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSentTransactions()
    {
        return $this->sentTransactions;
    }

    /**
     * Add receivedTransaction
     *
     * @param \AppBundle\Entity\Transaction $receivedTransaction
     *
     * @return Account
     */
    public function addReceivedTransaction(\AppBundle\Entity\Transaction $receivedTransaction)
    {
        $this->receivedTransactions[] = $receivedTransaction;

        return $this;
    }

    /**
     * Remove receivedTransaction
     *
     * @param \AppBundle\Entity\Transaction $receivedTransaction
     */
    public function removeReceivedTransaction(\AppBundle\Entity\Transaction $receivedTransaction)
    {
        $this->receivedTransactions->removeElement($receivedTransaction);
    }

    /**
     * Get receivedTransactions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReceivedTransactions()
    {
        return $this->receivedTransactions;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Account
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set card
     *
     * @param \AppBundle\Entity\Card $card
     *
     * @return Account
     */
    public function setCard(\AppBundle\Entity\Card $card = null)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get card
     *
     * @return \AppBundle\Entity\Card
     */
    public function getCard()
    {
        return $this->card;
    }
}
