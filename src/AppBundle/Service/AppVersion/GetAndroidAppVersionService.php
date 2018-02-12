<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class GetAndroidAppVersionService
 */
class GetAndroidAppVersionService
{
	protected $appVersionRepository;

	/**
	 * GetAndroidAppVersionService constructor.
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
		$version = $this->appVersionRepository->findOneByOs("android");

		return $version;
	}
}
