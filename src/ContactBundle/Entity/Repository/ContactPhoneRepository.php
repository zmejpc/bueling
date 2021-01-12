<?php

namespace ContactBundle\Entity\Repository;

use Doctrine\ORM\Query;
use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneRepository extends DashboardRepository implements ContactRepositoryInterface
{
    private function createQuery(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q');

        return $query;
    }

    public function allElementsForIndexDashboard(array $dataTable, array $listElementsForIndex): Query
    {
        $query = self::createQuery();

        return $query->getQuery();
    }

    public function countNewContactRequests(): int
    {
        return $this->createQueryBuilder('q')
            ->select('count(q.id)')
            ->where('q.status IS NULL')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getForFrontendAction(int $max_results)
    {
        $query = self::createQuery();
        $query
            ->where('q.showOnWebsite = true')
            ->orderBy('q.position')
            ->setMaxResults($max_results);

        return $query->getQuery()->getResult();
    }
}
