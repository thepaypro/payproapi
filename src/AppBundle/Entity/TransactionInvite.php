<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="TransactionInvites")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionInviteRepository")
 */
class TransactionInvite
{

    const STATUS_REQUESTED = 'REQUESTED';
    const STATUS_ACCEPTED = 'ACCEPTED';
    const STATUS_EXPIRED = 'EXPIRED';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Invite", inversedBy="transactionInvites")
     * @ORM\JoinColumn(name="invite_id", referencedColumnName="id")
     */
    protected $invite;

    /**
     * @ORM\OneToOne(targetEntity="BitcoinTransaction", inversedBy="transactionInvite")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

    /**
     * @var \DateTime
     * @ORM\Column(name="requested_at", type="datetime", nullable=false)
     */
    protected $requestedAt;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Choice(callback = "getValidStatuses")
     */
    protected $status;

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
     * @return array
     */
    public function getValidStatuses() : array
    {
        $constants = self::getConstants();
        $key_types =  array_filter(array_flip($constants), function ($k) {
            return (bool)preg_match('/STATUS_/', $k);
        });

        $document_types = array_intersect_key($constants, array_flip($key_types));
        return $document_types;
    }

    /**
     * Set requestedAt
     *
     * @param \DateTime $requestedAt
     *
     * @return TransactionInvite
     */
    public function setRequestedAt($requestedAt)
    {
        $this->requestedAt = $requestedAt;

        return $this;
    }

    /**
     * Get requestedAt
     *
     * @return \DateTime
     */
    public function getRequestedAt()
    {
        return $this->requestedAt;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return TransactionInvite
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set invite
     *
     * @param \AppBundle\Entity\Invite $invite
     *
     * @return TransactionInvite
     */
    public function setInvite(\AppBundle\Entity\Invite $invite = null)
    {
        $this->invite = $invite;

        return $this;
    }

    /**
     * Get invite
     *
     * @return \AppBundle\Entity\Invite
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * Set transaction
     *
     * @param \AppBundle\Entity\BitcoinTransaction $transaction
     *
     * @return TransactionInvite
     */
    public function setTransaction(\AppBundle\Entity\BitcoinTransaction $transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \AppBundle\Entity\BitcoinTransaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
