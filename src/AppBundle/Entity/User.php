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

    public function __construct()
    {
        parent::__construct();
    }

    public function jsonSerialize()
    {
        $allProperties = get_object_vars($this);

        unset($allProperties['usernameCanonical']);
        unset($allProperties['emailCanonical']);
        unset($allProperties['salt']);
        unset($allProperties['password']);
        unset($allProperties['plainPassword']);
        unset($allProperties['lastLogin']);
        unset($allProperties['confirmationToken']);
        unset($allProperties['passwordRequestedAt']);

        return $allProperties;
    }
}