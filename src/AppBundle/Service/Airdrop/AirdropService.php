<?php

namespace AppBundle\Service\Airdrop;

use AppBundle\Entity\Airdrop;
use AppBundle\Repository\AirdropRepository;


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
    	$airdropUsers = $this->airdropRepository->findAll();

    	foreach ($airdropUsers as $airdropUser) {
		    dump($airdropUser);
		}

		die();

    }
}
