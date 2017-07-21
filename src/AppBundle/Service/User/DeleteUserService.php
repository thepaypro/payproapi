<?php

namespace AppBundle\Service\User;

use AppBundle\Service\User\Validator\UserValidatorService;
use AppBundle\Repository\UserRepository;
use AppBundle\Exception\PayProException;

/**
 * Class DeleteUserService
 * @package AppBundle\Service\User
 */
class DeleteUserService
{
    private $userValidationService;
    private $userRepository;

    public function __construct(
        UserValidatorService $userValidatorService,
        UserRepository $userRepository
    )
    {
        $this->userValidatorService = $userValidatorService;
        $this->userRepository = $userRepository;
    }

    /**
     * Delete the user if it's possible.
     * @param  int $userId
     */
    public function execute(int $requesterUserId, int $userId) : Boolean
    {
        $userToBeDeleted = $this->userRepository->findOneById($userId);
        $requesterUser = $this->userRepository->findOneById($requesterUserId);

        if (!$account = $requesterUser->getAccount()) {
            throw new PayProException("User could not be deleted", 400);
        }

        $ownedUsers = $requesterUser->getAccount()->getUsers();

        if (!$ownedUsers->contains($userToBeDeleted)) {
            throw new PayProException("User could not be found", 404);
        }

        if ($ownedUsers->count() == 1) {
            throw new PayProException("Too few users, user could not be deleted", 400);
        }

        $this->userRepository->delete($userToBeDeleted);

        return true;
    }
}
