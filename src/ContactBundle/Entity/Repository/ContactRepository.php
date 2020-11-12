<?php

namespace ContactBundle\Entity\Repository;

use Doctrine\ORM\Query;
use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ContactRepository extends DashboardRepository implements ContactRepositoryInterface
{
    private function createQuery(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, status, status_t')
            ->leftJoin('q.status', 'status')
            ->leftJoin('status.translations', 'status_t');

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
}
