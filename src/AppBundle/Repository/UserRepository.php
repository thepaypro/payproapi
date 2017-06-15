<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class UserRepository extends EntityRepository
{
    public function findUsersWithUsernameIn(array $usernames = []) : array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query = $qb->select('u')->from('AppBundle\Entity\User', 'u');

        $queryOfUsersByUsernamesInArray = $this->queryUsersWithUsernameIn($query, $usernames);

        $query = $query->where($queryOfUsersByUsernamesInArray);

        return $query->getQuery()->getResult();
    }

    private function queryUsersWithUsernameIn(QueryBuilder $qb, array $usernames = [])
    {
        if (!$qb) {
            $qb = $this->getEntityManager()->createQueryBuilder();
        }

        $desiredUsernamesQueries = [];

        foreach ($usernames as $key => $username) {
            $desiredUsernamesQueries[] = $qb->expr()->like('u.username', '?'.$key);
            $qb->setParameter($key, '%'.$username.'%');
        }

        return $qb->expr()->orX()->addMultiple($desiredUsernamesQueries);
    }
}
