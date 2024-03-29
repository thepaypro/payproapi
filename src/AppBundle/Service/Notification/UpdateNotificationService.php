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
class UpdateNotificationService
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
     * Receives the required parameters of a notification, finds it in the repository
     * and updates it
     * @param int $notificationId
     * @param bool $isSent
     * @param int $accountId
     * @param string $deviceId
     * @return Notification
     * @throws PayProException
     */
    public function execute(
        int $notificationId,
        bool $isSent,
        int $accountId,
        string $deviceId
    ): Notification
    {
        $notification = $this->notificationRepository->findOneById($notificationId);

        if ($accountId) {
            $account = $this->accountRepository->findOneById($accountId);

            if (!$account) {
                throw new PayProException("No account found", 400);
            }

            $notification->setAccount($account);
        }

        $notification->setIsSent($isSent ? $isSent : $notification->getIsSent());
        $notification->setDeviceId($deviceId ? $deviceId : $notification->getDeviceId());

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
