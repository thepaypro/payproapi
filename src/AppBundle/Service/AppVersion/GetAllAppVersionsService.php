<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;


/**
 * Class GetAllAppVersionsService
 */
class GetAllAppVersionsService
{
	protected $appVersionRepository;

	/**
	 * GetAllAppVersionsService constructor.
	 * @param AppVersionRepository $appVersionRepository
	 */
	public function __construct(
		AppVersionRepository $appVersionRepository
	)
	{
		$this->appVersionRepository = $appVersionRepository;
	}

	/**
	 * @throws PayProException
	 */
	public function execute(): array
	{
		$versions = $this->appVersionRepository->findAll();

		return $versions;
	}
}
