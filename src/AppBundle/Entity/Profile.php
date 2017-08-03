<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Profiles")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProfileRepository")
 */
class Profile implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text", nullable=false)
     */
    protected $picture;

    /**
     * @ORM\OneToOne(targetEntity="Account", inversedBy="profile")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    protected $account;

    public function __construct(string $base64Picture, Account $account)
    {
        $this->account = $account;
        $this->picture = $base64Picture;
    }

    public function jsonSerialize()
    {
        $publicProperties['id'] = $this->id;
        $publicProperties['account'] = $this->account->getId();
        $publicProperties['picture'] = $this->picture;

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
     * Set picture
     *
     * @param string $picture
     *
     * @return Profile
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set account
     *
     * @param Account $account
     *
     * @return Profile
     */
    public function setAccount(Account $account)
    {
        $this->account = $account;

        return $this;
    }

    /**
     * Get account
     *
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }
}
