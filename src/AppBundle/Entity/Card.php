<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Cards")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CardRepository")
 */
class Card implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Account", inversedBy="card")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisCardCode;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isEnabled;

    public function __construct()
    {
    }

    public function jsonSerialize()
    {
        $allProperties = get_object_vars($this);

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
     * Set contisCardCode
     *
     * @param string $contisCardCode
     *
     * @return Card
     */
    public function setContisCardCode($contisCardCode)
    {
        $this->contisCardCode = $contisCardCode;

        return $this;
    }

    /**
     * Get contisCardCode
     *
     * @return string
     */
    public function getContisCardCode()
    {
        return $this->contisCardCode;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Card
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isEnabled
     *
     * @param boolean $isEnabled
     *
     * @return Card
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return boolean
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return Card
     */
    public function setAccount(\AppBundle\Entity\Account $account = null)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return \AppBundle\Entity\Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
