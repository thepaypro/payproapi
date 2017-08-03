<?php
namespace AppBundle\Service\Profile;

use AppBundle\Entity\Profile;
use AppBundle\Repository\ProfileRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;

/**
 * Class UpdateProfileService
 * @package AppBundle\Service
 */
class UpdateProfileService
{
    protected $userRepository;
    protected $profileRepository;

    /**
     * @param UserRepository    $userRepository
     * @param ProfileRepository $profileRepository
     */
    public function __construct(
        UserRepository $userRepository,
        ProfileRepository $profileRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->profileRepository = $profileRepository;
    }

    /**
     * Create the profile
     * 
     * @param  int      $profileId
     * @param  string   $picture
     * @return Profile
     * @throws PayProException
     */
    public function execute(int $profileId, string $picture) : Profile
    {
        $profile = $this->profileRepository->findOneById($profileId);

        if (!$profile) {
            throw new PayProException('Profile not found', 404);
        }

        $profile->setPicture($picture);

        $this->profileRepository->save($profile);

        return $profile;
    }
}
