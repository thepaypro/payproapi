<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="AppVersion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AppVersionRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields={"os"})
 */
class AppVersion implements \JsonSerializable
{
	/**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

	/**
	 * @ORM\Column(type="string", nullable=false)
	 */
	protected $os;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $lastVersion;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    protected $oldestSupportedVersion;


	public function __construct($os, $lastversion, $oldestSupportedVersion)
    {
        $this->os = $os;
        $this->lastversion = $lastversion;
        $this->oldestSupportedVersion = $oldestSupportedVersion;
    }

    public function jsonSerialize(){
    	$publicProperties['os'] = $this->os;
    	$publicProperties['lastVersion'] = $this->lastVersion;
    	$publicProperties['oldestSupportedVersion'] = $this->oldestSupportedVersion;

    	return $publicProperties;
    }

	/**
	 * Get os
	 * 
	 * @return string 
	 */
	public function getOs(){
		return $this->os;
	}

	/**
	 * Get last version
	 * 
	 * @return string
	 */
	public function getLastVersion(){
		return $this->lastVersion;
	}

	/**
	 * Set last version
	 * 
	 * @param string
	 * 
	 * @return AppVersion
	 */
	public function setLastVersion($lastVersion) {
		$this->lastVersion = $lastVersion;

		return $this;
	}

	/**
	 * Get oldest supported version
	 * 
	 * @return string
	 */
	public function getOldestSupportedVersion(){
		return $this->oldestSupportedVersion;
	}

	/**
	 * Set oldest supported version
	 * 
	 * @param string
	 * 
	 * @return AppVersion
	 */
	public function setOldestSupportedVersion($oldestSupportedVersion) {
		$this->oldestSupportedVersion = $oldestSupportedVersion;

		return $this;
	}

}

