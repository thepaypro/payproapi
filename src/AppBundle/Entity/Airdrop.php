<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Airdrop")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AirdropRepository")
 */
class Airdrop implements \JsonSerializable
{

	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

     /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $transactionHash;

     /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $walletAddr;

     /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $pips;

    public function __construct()
    {
     
    }

    public function jsonSerialize()
    {
    	$publicProperties['walletAddr'] = $this->walletAddr;
    	$publicProperties['pips'] = $this->pips;
    	$publicProperties['transactionHash'] = $this->transactionHash;

    	return $publicProperties;
    }

    public function getWalletAddr()
    {
    	return $this->walletAddr;
    }

    public function getPips()
    {
    	return $this->pips;
    }

    public function getTransactionHash()
    {
    	return $this->transactionHash;
    }

    public function setTransactionHash($transactionHash)
    {
    	$this->transactionHash = $transactionHash;
    }


}