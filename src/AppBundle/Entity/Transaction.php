<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use \DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="Transactions")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="sentTransactions")
     * @ORM\JoinColumn(name="payer_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $payer;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="receivedTransactions")
     * @ORM\JoinColumn(name="beneficiary_id", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    protected $beneficiary;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisTransactionId;

    /**
     * @ORM\Column(type="float", nullable=false)
     * @Assert\NotBlank()
     */
    protected $amount;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $subject;

    /**
     * @ORM\OneToOne(targetEntity="TransactionInvite", mappedBy="transaction")
     */
    protected $transactionInvite;

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
        DateTime $creationDate
    )
    {
        $this->payer = $payer;
        $this->beneficiary = $beneficiary;
        $this->amount = $amount;
        $this->subject = $subject;
        $this->createdAt = $creationDate;
        $this->updatedAt = $creationDate;
    }

    public function jsonSerialize()
    {
        $publicProperties = [
            'id' => $this->id,
            'payer' => $this->payer,
            'beneficiary' => $this->beneficiary,
            'amount' => $this->amount,
            'subject' => $this->subject,
            'transactionInvite' => $this->transactionInvite
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
     * Set contisTransactionId
     *
     * @param string $contisTransactionId
     *
     * @return Transaction
     */
    public function setContisTransactionId($contisTransactionId)
    {
        $this->contisTransactionId = $contisTransactionId;

        return $this;
    }

    /**
     * Get contisTransactionId
     *
     * @return string
     */
    public function getContisTransactionId()
    {
        return $this->contisTransactionId;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return Transaction
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set transactionInvite
     *
     * @param string $transactionInvite
     *
     * @return Transaction
     */
    public function setTransactionInvite($transactionInvite)
    {
        $this->transactionInvite = $transactionInvite;

        return $this;
    }

    /**
     * Get transactionInvite
     *
     * @return string
     */
    public function getTransactionInvite()
    {
        return $this->transactionInvite;
    }

    /**
     * Set payer
     *
     * @param \AppBundle\Entity\Account $payer
     *
     * @return Transaction
     */
    public function setPayer(\AppBundle\Entity\Account $payer = null)
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
     * @return Transaction
     */
    public function setBeneficiary(\AppBundle\Entity\Account $beneficiary = null)
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
     * @param String $subject
     *
     * @return Transaction
     */
    public function setSubject(String $subject = null)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return String
     */
    public function getSubject()
    {
        return $this->subject;
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
