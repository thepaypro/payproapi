<?php

namespace AppBundle\Repository;

use DateInterval;
use DateTime;

class AccountRepository extends BaseEntityRepository
{
    public function findAccountsWithPendingNotification(): array
    {
        $date = new DateTime("now");
        $minutesToSubstract = new DateInterval('PT20M');
        $date->sub($minutesToSubstract);

        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('a')->from('AppBundle\Entity\Account', 'a')
                    ->innerJoin('a.notifications', 'n', 'WITH', 'n.accountId = a.id')
                    ->where($qb->expr()->eq('n.isSent', 'false'))
                    ->andWhere($qb->expr()->lte( 'a.createdAt', $date));
    }
}
