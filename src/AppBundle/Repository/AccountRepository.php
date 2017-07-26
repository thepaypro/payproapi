<?php

namespace AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

class AccountRepository extends BaseEntityRepository
{
    public function findAccountsOlderThan20minutes() : array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('u')->from('AppBundle\Entity\User', 'u');

        $queryOfUsersByUsernamesInArray = $this->queryUsersWithUsernameIn($query);

        $query = $query->where($queryOfUsersByUsernamesInArray);

        return $query->getQuery()->getResult();
    }
}
