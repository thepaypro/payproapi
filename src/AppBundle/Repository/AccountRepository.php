<?php

namespace AppBundle\Repository;

class AccountRepository extends BaseEntityRepository
{
    public function findAccountsOlderThan20minutes(): array
    {
        //TODO: Adapt the query with query builder to find the desired accounts.
//        $qb = $this->getEntityManager()->createQueryBuilder();
//        $query = $qb->select('a')->from('AppBundle\Entity\Account', 'a');
//        ...
    }
}
