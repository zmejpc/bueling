<?php

namespace ContactBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Design studio origami <https://origami.ua>
 */
class CallbackManagerRepository extends DashboardRepository implements CallbackManagerRepositoryInterface
{
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}
