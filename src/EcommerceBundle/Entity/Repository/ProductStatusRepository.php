<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductStatusRepository extends DashboardRepository
{
    public function getProductStatusesForOrderForm()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't');

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
