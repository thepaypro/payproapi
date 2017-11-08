<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="BitcoinTransactions")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BitcoinTransactionRepository")
 */
class BitcoinTransaction implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BitcoinAccount", inversedBy="sentTransactions")
     * @ORM\JoinColumn(name="payer_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $payer;

    /**
     * @ORM\ManyToOne(targetEntity="BitcoinAccount", inversedBy="receivedTransactions")
     * @ORM\JoinColumn(name="beneficiary_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $beneficiary;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $blockchainTransactionId;

    /**
     * @ORM\Column(type="decimal", nullable=false, precision=20, scale=2)
     * @Assert\NotBlank()
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $subject;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $addressTo;

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
        Account $payer = null,
        Account $beneficiary = null,
        float $amount,
        string $subject,
        string $addressTo = null,
        DateTime $creationDate = null
    )
    {
        $this->payer = $payer;
        $this->beneficiary = $beneficiary;
        $this->amount = $amount;
        $this->subject = $subject;
        $this->addressTo = $addressTo;
        $this->createdAt = $creationDate;
        $this->updatedAt = $creationDate;
    }

    public function jsonSerialize()
    {
        $publicProperties = [
            'id' => $this->id,
            'payer' => isset($this->payer) ? $this->payer->getId() : $this->payer,
            'beneficiary' => isset($this->beneficiary) ? $this->beneficiary->getId() : $this->beneficiary,
            'amount' => $this->amount,
            'subject' => $this->subject,
            'addressTo' => $this->addressTo,
            'createdAt' => $this->createdAt
        ];

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
     * Set blockchainTransactionId
     *
     * @param string $blockchainTransactionId
     *
     * @return BitcoinTransaction
     */
    public function setBlockchainTransactionId($blockchainTransactionId)
    {
        $this->blockchainTransactionId = $blockchainTransactionId;

        return $this;
    }

    /**
     * Get blockchainTransactionId
     *
     * @return string
     */
    public function getBlockchainTransactionId()
    {
        return $this->blockchainTransactionId;
    }

    /**
     * Set amount
     *
     * @param decimal $amount
     *
     * @return BitcoinTransaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return decimal
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set payer
     *
     * @param \AppBundle\Entity\Account $payer
     *
     * @return BitcoinTransaction
     */
    public function setPayer(\AppBundle\Entity\BitcoinAccount $payer = null)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return \AppBundle\Entity\Account
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set beneficiary
     *
     * @param \AppBundle\Entity\Account $beneficiary
     *
     * @return BitcoinTransaction
     */
    public function setBeneficiary(\AppBundle\Entity\BitcoinAccount $beneficiary = null)
    {
        $this->beneficiary = $beneficiary;

        return $this;
    }

    /**
     * Get beneficiary
     *
     * @return \AppBundle\Entity\Account
     */
    public function getBeneficiary()
    {
        return $this->beneficiary;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return BitcoinTransaction
     */
    public function setSubject(string $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $addressTo
     */
    public function setAddressTo($addressTo)
    {
        $this->addressTo = $addressTo;
    }

    /**
     * @return string
     */
    public function getAddressTo()
    {
        return $this->addressTo;
    }

   /**
     * Gets triggered only on insert
     *
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->createdAt = $this->createdAt ? $this->createdAt : new \DateTime("now");
        $this->updatedAt = $this->createdAt;
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
}
