<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class SetAndroidAppVersionService
 */
class SetAndroidAppVersionService
{
	protected $appVersionRepository;

	/**
	 * SetAndroidAppVersionService constructor
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
		$version = $this->appVersionRepository->findOneByOs("android");

		$version->setLastVersion($lastVersion);
		$version->setOldestSupportedVersion($oldestSupportedVersion);

		$this->appVersionRepository->save($version);

		return $version;
	}
}
