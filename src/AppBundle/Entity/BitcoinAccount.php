<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="BitcoinAccounts")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BitcoinAccountRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"address"})
 */
class BitcoinAccount implements \JsonSerializable
{

	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
     * @ORM\OneToMany(targetEntity="User", mappedBy="bitcoinAccount")
     */
    private $users;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $address;

    /**
     * @ORM\OneToMany(targetEntity="BitcoinTransaction", mappedBy="payer")
     */
    protected $sentTransactions;

    /**
     * @ORM\OneToMany(targetEntity="BitcoinTransaction", mappedBy="beneficiary")
     */
    protected $receivedTransactions;

    /**
     * @ORM\ManyToOne(targetEntity="BitcoinTransaction")
     * @ORM\JoinColumn(name="last_synced_transaction_id", referencedColumnName="id")
     */
    protected $lastSyncedTransaction;

	public function __construct(
        User $user
    )
    {
        $this->sentTransactions = new ArrayCollection();
        $this->receivedTransactions = new ArrayCollection();
        $this->users = new ArrayCollection();

    	$this->users[] = $user;
    }

    public function jsonSerialize()
    {
    	$publicProperties['users'] = $this->users->map(function (User $user) {
            return $user->getId();
        })->toArray();
        $publicProperties['id'] = $this->id;
        $publicProperties['address'] = $this->address;

        return $publicProperties;
    }

    public function jsonSerializeBasic()
    {
        $publicProperties['id'] = $this->id;
        $publicProperties['address'] = $this->address;

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
     * @return ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

	/**
	 * Set Address
	 * 
	 * @param integer $address
	 * @return integer
	 */
	public function setAddress($address)
	{
		$this->address = $address;
        // dump($this->address);dump($address);die();
	}

    /**
     * Get Address
     * 
     * @return string
     */
    public function getAddress()
    {
    	return $this->address;
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
     * Add sentTransaction
     *
     * @param \AppBundle\Entity\BitcoinTransaction $sentTransaction
     *
     * @return BitcoinAccount
     */
    public function addSentTransaction(BitcoinTransaction $sentTransaction)
    {
        $this->sentTransactions[] = $sentTransaction;

        return $this;
    }

    /**
     * Remove sentTransaction
     *
     * @param \AppBundle\Entity\BitcoinTransaction $sentTransaction
     */
    public function removeSentTransaction(BitcoinTransaction $sentTransaction)
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
     * @param \AppBundle\Entity\BitcoinTransaction $receivedTransaction
     *
     * @return BitcoinAccount
     */
    public function addReceivedTransaction(BitcoinTransaction $receivedTransaction)
    {
        $this->receivedTransactions[] = $receivedTransaction;

        return $this;
    }

    /**
     * Remove receivedTransaction
     *
     * @param \AppBundle\Entity\BitcoinTransaction $receivedTransaction
     */
    public function removeReceivedTransaction(BitcoinTransaction $receivedTransaction)
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
     * @return BitcoinTransaction
     */
    public function getLastSyncedTransaction()
    {
        return $this->lastSyncedTransaction;
    }
    
    /**
     * @param mixed $lastSyncedTransaction
     */
    public function setLastSyncedTransaction($lastSyncedTransaction)
    {
        $this->lastSyncedTransaction = $lastSyncedTransaction;
    }
}