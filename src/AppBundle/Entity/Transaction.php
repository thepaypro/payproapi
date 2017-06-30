<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Transactions")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransactionRepository")
 */
class Transaction implements \JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="sentTransactions")
     * @ORM\JoinColumn(name="payer_id", referencedColumnName="id")
     */
    protected $payer;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="receivedTransactions")
     * @ORM\JoinColumn(name="beneficiary_id", referencedColumnName="id")
     */
    protected $beneficiary;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $contisCode;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    protected $amount;

    public function jsonSerialize()
    {
        $allProperties = get_object_vars($this);

        unset($allProperties['contisCode']);

        return $allProperties;
    }
}
