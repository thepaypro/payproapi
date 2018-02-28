<?php

namespace AppBundle\Service\Airdrop;

use AppBundle\Entity\Airdrop;
use AppBundle\Repository\AirdropRepository;
use PayPro\Ethereum\EthereumClientRPC;
use PayPro\Ethereum\Types\Address;
use PayPro\Ethereum\Types\Units\ERC20BasicUnit;
use PayPro\Ethereum\Exception\EthereumException;


/**
 * Class AirdropService
 */
class AirdropService
{
	protected $airdropRepository;

	/**
     * AirdropService constructor.
     * @param AirdropRepository $airdropRepository
     */
	public function __construct(AirdropRepository $airdropRepository)
    {
        $this->airdropRepository = $airdropRepository;
    }


    public function execute()
    {
    	try{

	    	$airdropUsers = $this->airdropRepository->findAll();

	    	$this->client = new EthereumClientRPC("http://192.168.1.107:8545");
	    	$this->accounts = $this->client->eth()->accounts();
	    	$this->client->setupWallet($this->accounts[69]);
	    	$tokenContractAddress = new Address("0xf2beae25b23f0ccdd234410354cb42d08ed54981");

	    	foreach ($airdropUsers as $airdropUser) {
	    		usleep(500000);
	    		if(is_null($airdropUser->getTransactionHash())){
	    			$transactionHash = $this->client->ERC20Wallet()->sendERC20($tokenContractAddress, new Address(trim($airdropUser->getWalletAddr())), new ERC20BasicUnit($airdropUser->getPips(),18), null, null);

	    			$airdropUser->setTransactionHash($transactionHash);

	    			$this->airdropRepository->save($airdropUser);
	    		}
			}
			return $airdropUsers;

		} catch (EthereumException $e){
            throw $e;
        } catch (\TypeError $e) {
			throw new EthereumException($e->getMessage(),1001,400);
		} catch (Exception $e) {
            throw new EthereumException ($e->getMessage(),5000,500);
        }	
    }
}
