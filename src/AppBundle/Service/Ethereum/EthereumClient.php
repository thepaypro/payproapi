<?php

namespace AppBundle\Service\Ethereum;

// use Service\Ethereum\Methods\Net;
// use Service\Ethereum\Methods\Personal;
// use Service\Ethereum\Methods\Shh;
use AppBundle\Service\Ethereum\Methods\Eth;
use AppBundle\Service\Ethereum\Methods\Web3;

/**
 * Class EthereumClient
 * @package AppBundle\Service\Ethereum
 */
class EthereumClient
{
    protected $eth;
    protected $web3;

    public function __construct(
        Eth $eth,
        Web3 $web3
    ){
        $this->eth = $eth;
        $this->web3 = $web3;
    }

    public function eth(): Eth
    {
        return $this->eth;
    }

    public function web3(): Web3
    {
        return $this->web3;
    }
}