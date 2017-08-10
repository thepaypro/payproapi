<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Account;

class TransactionRepository extends BaseEntityRepository
{
    public function getTransactionsOfAccount(
        Account $account,
        int $page = 0,
        int $size = 10)
    {
        $firstResult = ($page - 1) * $size;

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\Transaction', 't');

        $qb->setParameter('account', $account);

        $query = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':account'),
            $qb->expr()->eq('t.beneficiary', ':account')
        ]);

        $qb = $qb->where($query);
        $qb->addOrderBy('t.createdAt', 'DESC');
        $qb->setMaxResults($size);
        $qb->setFirstResult($firstResult);
        $content = $qb->getQuery()->getResult();

        return [
            'content' => $content,
            'page' => $page,
            'size' => $size
        ];
    }

    public function getTransactionsOfAccountAfterTransactionId(
        Account $account,
        int $transactionId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\Transaction', 't');

        $qb->setParameters(array('account' => $account, 'transactionId' => $transactionId));

        $query1 = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':account'),
            $qb->expr()->eq('t.beneficiary', ':account')
        ]);

        $query = $qb->expr()->andX()->addMultiple([
            $qb->expr()->gt('t.id', ':transactionId'),
            $query1
        ]);

        $qb = $qb->where($query);
        $qb->addOrderBy('t.createdAt', 'DESC');
        $content = $qb->getQuery()->getResult();

        return [
            'content' => $content,
        ];
    }
}
