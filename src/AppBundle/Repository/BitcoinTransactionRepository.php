<?php

namespace AppBundle\Repository;

use AppBundle\Entity\BitcoinAccount;

class BitcoinTransactionRepository extends BaseEntityRepository
{
    public function getTransactionsOfAccount(
        BitcoinAccount $bitcoinAccount,
        int $page,
        int $size)
    {
        $firstResult = ($page - 1) * $size;

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\BitcoinTransaction', 't');

        $qb->setParameter('bitcoinAccount', $bitcoinAccount);

        $query = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':bitcoinAccount'),
            $qb->expr()->eq('t.beneficiary', ':bitcoinAccount')
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

    public function getAllTransactionsOfAccount(
        BitcoinAccount $bitcoinAccount)
    {

        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\BitcoinTransaction', 't');

        $qb->setParameter('bitcoinAccount', $bitcoinAccount);

        $query = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':bitcoinAccount'),
            $qb->expr()->eq('t.beneficiary', ':bitcoinAccount')
        ]);

        $qb = $qb->where($query);
        $qb->addOrderBy('t.createdAt', 'DESC');
        $content = $qb->getQuery()->getResult();

        return [
            'content' => $content
        ];
    }



    public function getTransactionsOfAccountAfterTransactionId(
        BitcoinAccount $bitcoinAccount,
        int $transactionId)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb = $qb->select('t')->from('AppBundle\Entity\BitcoinTransaction', 't');

        $qb->setParameters([
            'bitcoinAccount' => $bitcoinAccount,
            'transactionId' => $transactionId
        ]);

        $query1 = $qb->expr()->orX()->addMultiple([
            $qb->expr()->eq('t.payer', ':bitcoinAccount'),
            $qb->expr()->eq('t.beneficiary', ':bitcoinAccount')
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
