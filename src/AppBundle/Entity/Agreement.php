<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Agreements")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgreementRepository")
 */
class Agreement implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisAgreementCode;

    /**
     * @ORM\OneToMany(targetEntity="Account", mappedBy="agreement")
     */
    protected $accounts;

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
