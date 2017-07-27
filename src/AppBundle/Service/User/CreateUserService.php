<?php

namespace AppBundle\Service\User;

USE FOS\UserBundle\Model\UserManagerInterface;

use AppBundle\Service\User\Validator\UserValidatorService;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;

class CreateUserService
{
    private $userValidationService;
    private $userRepository;
    private $userManager;

    public function __construct(
        UserValidatorService $userValidatorService,
        UserRepository $userRepository,
        UserManagerInterface $userManager
    )
    {
        $this->userValidatorService = $userValidatorService;
        $this->userRepository = $userRepository;
        $this->userManager = $userManager;
    }

    /**
     * Insert in database the user with the given phoneNumber (if it's valid).
     * @param  GetResponseUserEvent $event
     */
    public function execute(int $requesterUserId, string $phoneNumber, string $verificationCode)
    {
        $requesterUser = $this->userRepository->findOneById($requesterUserId);

        if (!$account = $requesterUser->getAccount()) {
            throw new PayProException('User could not be created', 400);
        }

        if ($requesterUser->getAccount()->getUsers()->count() == 2) {
            throw new PayProException('Too many users, user could not be created', 400);
        }

        if (!$this->userValidatorService->validate($phoneNumber, $verificationCode)) {
            throw new PayProException('User could not be created', 400);
        }

        $user = $this->userManager->createUser();

        $user->setUsername($phoneNumber);
        $user->setUsernameCanonical($phoneNumber);
        $user->setAccount($account);
        $user->setPassword($requesterUser->getPassword());

        $this->userRepository->save($user);

        return $user;
    }
}
