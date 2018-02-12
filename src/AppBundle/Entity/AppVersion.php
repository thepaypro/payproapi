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
    protected $version;


	public function __construct($os, $version)
    {
        $this->os = $os;
        $this->version = $version;
    }

    public function jsonSerialize(){
    	$publicProperties['os'] = $this->os;
    	$publicProperties['version'] = $this->version;

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
	 * Get version
	 * 
	 * @return string
	 */
	public function getVersion(){
		return $this->version;
	}


	/**
	 * Set version
	 * 
	 * @param string
	 * 
	 * @return AppVersion
	 */
	public function setVersion($version) {
		$this->version = $version;

		return $this;
	}

}

