<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackRepository extends DashboardRepository implements CallbackRepositoryInterface
{
    private function createQuery()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, status, status_t')
            ->leftJoin('q.status', 'status')
            ->leftJoin('status.translations', 'status_t');

        return $query;
    }

    public function countNewCallbackRequests()
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
