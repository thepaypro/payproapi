<?php

namespace AppBundle\Service\Notification;

use AppBundle\Entity\Notification;
use AppBundle\Exception\PayProException;
use AppBundle\Repository\AccountRepository;
use AppBundle\Repository\NotificationRepository;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class CreateNotificationService
 */
class CreateNotificationService
{
    protected $accountRepository;
    protected $notificationRepository;
    protected $validationService;

    public function __construct(
        AccountRepository $accountRepository,
        NotificationRepository $notificationRepository,
        ValidatorInterface $validationService
    )
    {
        $this->accountRepository = $accountRepository;
        $this->notificationRepository = $notificationRepository;
        $this->validationService = $validationService;
    }

    /**
     * Receives an accountId and a deviceId and creates a notification with that information.
     * @param int $accountId
     * @param string $deviceId
     * @return Notification
     * @throws PayProException
     */
    public function execute(
        int $accountId,
        string $deviceId
    ): Notification
    {
        $account = $this->accountRepository->findOneById($accountId);

        if (!$account) {
            throw new PayProException("No account found", 400);
        }

        $notification = new Notification(
            $account,
            false,
            $deviceId
        );

        $errors = $this->validationService->validate($notification);

        if (count($errors) > 0) {
            foreach ($errors as $key => $error) {
                throw new PayProException($error->getPropertyPath() . ': ' . $error->getMessage(), 400);
            }
        }

        $this->notificationRepository->save($notification);

        return $notification;
    }
}
