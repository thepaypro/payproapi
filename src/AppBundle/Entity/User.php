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
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nickname;

    public function __construct()
    {
        parent::__construct();
    }

    public function jsonSerialize()
    {
            $publicProperties = [
                'id' => $this->id,
                'username' => $this->username,
                'nickname' => $this->nickname,
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
     * Set nickname
     *
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;

        return $this;
    }

    /**
     * Get nickname
     *
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
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
