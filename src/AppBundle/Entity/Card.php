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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $contisCardId;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $contisCardActivationCode;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isActive;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $isEnabled;

    public function __construct(Account $account, bool $isActive, bool $isEnabled)
    {
        $this->account = $account;
        $this->isActive = $isActive;
        $this->isEnabled = $isEnabled;
    }

    public function jsonSerialize()
    {
        $publicProperties = [
            'id' => $this->id,
            'account' => $this->account->getId(),
            'isActive' => $this->isActive,
            'isEnabled' => $this->isEnabled
        ]; 

        return $publicProperties;
    }

    public function jsonSerializeBasic()
    {
        $cardStatus = 0;

        if ($this->isActive){
            if ($this->isEnabled){
                $cardStatus = 2;
            }else{
                $cardStatus = 3;
            }
        }else{
            $cardStatus = 1;
        }
        $publicProperties = [
            'id' => $this->id,
            'cardStatus' => $cardStatus
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
     * Set contisCardId
     *
     * @param string $contisCardId
     *
     * @return Card
     */
    public function setContisCardId($contisCardId)
    {
        $this->contisCardId = $contisCardId;

        return $this;
    }

    /**
     * Get contisCardId
     *
     * @return string
     */
    public function getContisCardId()
    {
        return $this->contisCardId;
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

    /**
     * Set contisCardActivationCode
     *
     * @param string $contisCardActivationCode
     *
     * @return Card
     */
    public function setContisCardActivationCode($contisCardActivationCode)
    {
        $this->contisCardActivationCode = $contisCardActivationCode;

        return $this;
    }

    /**
     * Get contisCardActivationCode
     *
     * @return string
     */
    public function getContisCardActivationCode()
    {
        return $this->contisCardActivationCode;
    }
}
