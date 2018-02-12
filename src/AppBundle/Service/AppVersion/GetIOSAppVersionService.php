<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class GetIOSAppVersionService
 */
class GetIOSAppVersionService
{
	protected $appVersionRepository;

	/**
	 * GetIOSAppVersionService constructor.
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
	public function execute(): AppVersion
	{
		$version = $this->appVersionRepository->findOneByOs("ios");

		return $version;
	}
}
