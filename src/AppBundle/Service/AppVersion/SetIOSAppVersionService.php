<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class SetIOSAppVersionService
 */
class SetIOSAppVersionService
{
	protected $appVersionRepository;

	/**
	 * SetIOSAppVersionService constructor
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
	public function execute(string $lastVersion, string $oldestSupportedVersion): AppVersion
	{
		$version = $this->appVersionRepository->findOneByOs("ios");

		$version->setLastVersion($lastVersion);
		$version->setOldestSupportedVersion($oldestSupportedVersion);

		$this->appVersionRepository->save($version);

		return $version;
	}
}
