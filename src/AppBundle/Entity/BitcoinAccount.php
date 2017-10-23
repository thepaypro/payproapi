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
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $address;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $balance;

	public function __construct(
        User $user,
        string $address
    )
    {
    	$this->users[] = $user;
    	$this->address = $address;
    }

    public function jsonSerialize()
    {
    	$publicProperties['users'] = $this->users->map(function (User $user) {
            return $user->getId();
        })->toArray();
        $publicProperties['id'] = $this->id;
        $publicProperties['address'] = $this->address;
        $publicProperties['balance'] = $this->balance;

        return $publicProperties;
    }

    public function jsonSerializeBasic()
    {
        $publicProperties['id'] = $this->id;
        $publicProperties['address'] = $this->address;
        $publicProperties['balance'] = $this->balance;

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
     * Set balance
     *
     * @param integer $balance
     * @return integer
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return integer
     */
    public function getBalance()
    {
        return $this->balance;
    }
}