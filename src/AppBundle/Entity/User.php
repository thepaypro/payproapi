<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="Users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\AttributeOverrides({
 *     @ORM\AttributeOverride(name="emailCanonical",
 *         column=@ORM\Column(
 *             nullable=true
 *         )
 *     ),
 *     @ORM\AttributeOverride(name="email",
 *         column=@ORM\Column(
 *             nullable=true
 *         )
 *     )
 * })
 */
class User extends BaseUser implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BitcoinAccount", inversedBy="users")
     * @ORM\JoinColumn(name="bitcoin_account_id", referencedColumnName="id")
     */
    private $bitcoinAccount;

    /**
     * @ORM\OneToMany(targetEntity="Invite", mappedBy="inviter")
     */
    protected $invites;

    /**
     * @ORM\OneToOne(targetEntity="Profile", mappedBy="user")
     */
    protected $profile;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $forename;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $lastname;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime(format="d/m/Y")
     */
    protected $birthDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Assert\NotBlank()
     */
    protected $documentNumber;

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
     * @ORM\ManyToOne(targetEntity="Country", inversedBy="users", cascade={"all"})
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     * @Assert\NotBlank()
     */
    protected $country;

    public function __construct()
    {
        parent::__construct();
    }

    public function jsonSerialize()
    {
            $publicProperties = [
                'id' => $this->id,
                'username' => $this->username,
                'forename' => $this->forename,
                'lastname' => $this->lastname,
                'birth_date' => $this->birthDate,
                
                'bitcoinAccount' => isset($this->bitcoinAccount) ? $this->bitcoinAccount->jsonSerializeBasic() : NULL
            ];

            return $publicProperties;
    }

    /**
     * Set bitcoinAccount
     *
     * @param \AppBundle\Entity\BitcoinAccount $bitcoinAccount
     *
     * @return User
     */
    public function setBitcoinAccount(BitcoinAccount $bitcoinAccount = null)
    {
        $this->bitcoinAccount = $bitcoinAccount;

        return $this;
    }

    /**
     * Get bitcoinAccount
     *
     * @return \AppBundle\Entity\BitcoinAccount
     */
    public function getBitcoinAccount()
    {
        return $this->bitcoinAccount;
    }

    /**
     * Add invite
     *
     * @param \AppBundle\Entity\Invite $invite
     *
     * @return User
     */
    public function addInvite(Invite $invite)
    {
        $this->invites[] = $invite;

        return $this;
    }

    /**
     * Remove invite
     *
     * @param \AppBundle\Entity\Invite $invite
     */
    public function removeInvite(Invite $invite)
    {
        $this->invites->removeElement($invite);
    }

    /**
     * Get invites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvites()
    {
        return $this->invites;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}
