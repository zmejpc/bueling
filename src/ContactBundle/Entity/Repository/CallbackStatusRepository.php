<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackStatusRepository extends DashboardRepository implements CallbackStatusRepositoryInterface
{
    public function getCallbackStatusesForCallbackForm()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->addOrderBy('q.position', 'asc');

        return $query;
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}
