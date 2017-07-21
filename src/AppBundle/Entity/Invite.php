<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="Invites")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InviteRepository")
 */
class Invite
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @Assert\NotBlank()
     */
    protected $invitedPhoneNumber;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="invites")
     * @ORM\JoinColumn(name="inviter_id", referencedColumnName="id", nullable=false)
     * @Assert\NotBlank()
     */
    protected $inviter;

    /**
     * @ORM\OneToMany(targetEntity="TransactionInvite", mappedBy="invite")
     */
    protected $transactionInvites;

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
     * Set invitedPhoneNumber
     *
     * @param string $invitedPhoneNumber
     *
     * @return Invite
     */
    public function setInvitedPhoneNumber($invitedPhoneNumber)
    {
        $this->invitedPhoneNumber = $invitedPhoneNumber;

        return $this;
    }

    /**
     * Get invitedPhoneNumber
     *
     * @return string
     */
    public function getInvitedPhoneNumber()
    {
        return $this->invitedPhoneNumber;
    }

    /**
     * Set inviter
     *
     * @param \AppBundle\Entity\User $inviter
     *
     * @return Invite
     */
    public function setInviter(User $inviter)
    {
        $this->inviter = $inviter;

        return $this;
    }

    /**
     * Get inviter
     *
     * @return \AppBundle\Entity\User
     */
    public function getInviter()
    {
        return $this->inviter;
    }
}
