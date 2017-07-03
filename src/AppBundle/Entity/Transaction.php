<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Transactions")
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
     */
    protected $payer;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="receivedTransactions")
     * @ORM\JoinColumn(name="beneficiary_id", referencedColumnName="id")
     */
    protected $beneficiary;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisCode;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $amount;

    /**
     * @ORM\OneToOne(targetEntity="TransactionInvite", mappedBy="transaction")
     * @ORM\Column(nullable=false)
     */
    protected $transactionInvite;

    public function jsonSerialize()
    {
        $allProperties = get_object_vars($this);

        unset($allProperties['contisCode']);

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
     * Set contisCode
     *
     * @param string $contisCode
     *
     * @return Transaction
     */
    public function setContisCode($contisCode)
    {
        $this->contisCode = $contisCode;

        return $this;
    }

    /**
     * Get contisCode
     *
     * @return string
     */
    public function getContisCode()
    {
        return $this->contisCode;
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
}
