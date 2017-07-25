<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="users")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity="Invite", mappedBy="inviter")
     */
    protected $invites;

    public function __construct()
    {
        parent::__construct();
    }

    public function jsonSerialize()
    {
        $publicProperties = [
            'id' => $this->id,
            'username' => $this->username,
            'account' => $this->account,
            'invites' => $this->invites
        ];

        return $publicProperties;
    }

    /**
     * Set account
     *
     * @param \AppBundle\Entity\Account $account
     *
     * @return User
     */
    public function setAccount(Account $account = null)
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
}
