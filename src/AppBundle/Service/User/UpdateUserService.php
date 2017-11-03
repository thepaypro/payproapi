<?php

namespace AppBundle\Service\User;

USE FOS\UserBundle\Model\UserManagerInterface;

use AppBundle\Service\User\Validator\UserValidatorService;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;

class UpdateUserService
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
    public function execute(int $requesterUserId, string $nickname)
    {
        $requesterUser = $this->userRepository->findOneById($requesterUserId);

        if ($requesterUser->getBitcoinAccount()->getUsers()->count() == 2) {
            throw new PayProException('Too many users, user could not be created', 400);
        }

        if (!is_string($nickname) || strlen($nickname) > 255){
            throw new PayProException("invalid nickname format", 400);
        }

        $requesterUser->setNickname($nickname);

        $this->userRepository->save($requesterUser);

        return $requesterUser;
    }
}
