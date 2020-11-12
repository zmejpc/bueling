<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactStatusRepository extends DashboardRepository implements ContactStatusRepositoryInterface
{
    public function getContactStatusesForContactForm(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->addOrderBy('q.position', 'asc');

        return $query;
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = $this->createQueryBuilder('q');
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}