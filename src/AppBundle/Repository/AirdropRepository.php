<?php

namespace AppBundle\Repository;

class AirdropRepository extends BaseEntityRepository
{
	public function getNextBeneficiary(){

		$em = $this->getEntityManager();
		$query = $em->createQuery(
		    'SELECT p
		       FROM AppBundle:Airdrop p
		      WHERE p.transactionHash IS NULL'
		);

		$query->setMaxResults(1);
		

		// $query = $em->createQuery('SELECT * FROM AppBundle\Entity\Airdrop WHERE transaction_hash IS NULL LIMIT 1');

		return $query->getResult();

	}
}