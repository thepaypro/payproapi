<?php

namespace AppBundle\Service\AppVersion;

use AppBundle\Entity\AppVersion;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AppVersionRepository;

/**
 * Class CheckIOSAppVersionService
 */
class CheckIOSAppVersionService
{
	protected $appVersionRepository;

	/**
	 * CheckIOSAppVersionService constructor.
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
	public function execute($app_version) : bool 
	{

		if(!isset($app_version)){
			throw new PayProException("need_to_specify_an_app_version", 400);
		}

		$oldest_supported_version_array = explode('.', $this->appVersionRepository->findOneByOs("ios")->getOldestSupportedVersion());

		if (count($oldest_supported_version_array) > 3){
			throw new PayProException("oldest_supported_version not valid", 500);
		}

		$app_version_array = explode('.', $app_version);

		if (count($app_version_array) > 3){
			throw new PayProException("app_version not valid", 400);
		}

		$needToUpdate = false;

		for ($i = 0; $i < min(count($oldest_supported_version_array), count($app_version_array)); $i++)
	    {
	        $oldest_supported_version_component = $oldest_supported_version_array[$i];
	        $app_version_component = $app_version_array[$i];
	        
	        if ($oldest_supported_version_component != $app_version_component)
	        {
	            $needToUpdate = ($app_version_component < $oldest_supported_version_component);
	            break;
	        }
	    }

		return $needToUpdate;
	}
}
