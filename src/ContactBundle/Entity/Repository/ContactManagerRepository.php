<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactManagerRepository extends DashboardRepository implements ContactManagerRepositoryInterface
{
	public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = $this->createQueryBuilder('q');
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}