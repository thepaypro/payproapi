<?php

namespace AppBundle\Repository;

use DateInterval;
use DateTime;
use Doctrine\DBAL\Types\Type;

class AccountRepository extends BaseEntityRepository
{
    public function findAccountsWithPendingNotification(): array
    {
        $date = new DateTime("now");
        $minutesToSubstract = new DateInterval('PT20M');
        $date->sub($minutesToSubstract);
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('a')->from('AppBundle\Entity\Account', 'a')
                    ->innerJoin('AppBundle\Entity\Notification', 'n', 'WITH', 'n.account = a.id')
                    ->where($qb->expr()->eq('n.isSent', 'false'))
                    ->andWhere($qb->expr()->lte( 'a.createdAt', ':date'))
                    ->getQuery()
                    ->setParameter('date', $date, Type::DATETIME);

        return $query->execute();
    }
}
