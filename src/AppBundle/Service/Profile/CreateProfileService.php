<?php
namespace AppBundle\Service\Profile;

use AppBundle\Entity\Profile;
use AppBundle\Repository\ProfileRepository;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;

/**
 * Class CreateProfileService
 * @package AppBundle\Service
 */
class CreateProfileService
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
     * @param  int      $userId
     * @param  string   $picture
     * @return Profile
     * @throws PayProException
     */
    public function execute(int $userId, string $picture) : Profile
    {
        $user = $this->userRepository->findOneById($userId);
        $account = $user->getAccount();

        if (!$account) {
            throw new PayProException('Account not found', 404);
        }
        if (!imagecreatefromstring(base64_decode($picture))) {
            throw new PayProException('Invalid image', 400);
        }

        $profile = new Profile($picture, $account);
        $this->profileRepository->save($profile);

        return $profile;
    }
}
