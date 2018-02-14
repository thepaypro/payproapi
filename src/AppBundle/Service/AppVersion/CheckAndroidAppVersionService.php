<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class CheckAndroidAppVersionService
 */
class CheckAndroidAppVersionService
{
	protected $appVersionRepository;

	/**
	 * CheckAndroidAppVersionService constructor.
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
	public function execute($app_version): bool
	{
		if(!isset($app_version)){
			throw new PayProException("need_to_specify_an_app_version", 400);
		}

		$oldest_supported_version = $this->appVersionRepository->findOneByOs("android")->getOldestSupportedVersion();

		return $app_version < $oldest_supported_version;
	}
}
