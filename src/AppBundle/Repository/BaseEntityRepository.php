<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

abstract class BaseEntityRepository extends EntityRepository {

    public function save($entity)
	{
        $this->_em->persist($entity);
        $this->_em->flush();
    }
}
