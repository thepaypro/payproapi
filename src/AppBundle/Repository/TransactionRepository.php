<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Account;
use Doctrine\ORM\QueryBuilder;

class TransactionRepository extends BaseEntityRepository
{
    public function getTransactionsOfAccount(Account $account)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\Transaction', 't');

        $qb->setParameter('account', $account);

        $query = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':account'),
            $qb->expr()->eq('t.beneficiary', ':account')
        ]);

        $qb = $qb->where($query);

        return $qb->getQuery()->getResult();
    }
}
