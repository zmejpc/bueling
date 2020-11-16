<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\ActivityArea;

/**
 * ActivityAreaRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class ActivityAreaRepository extends DashboardRepository
{
    private function createQuery()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't');

        return $query;
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();

        $query
            ->where('q.id =:id')
            ->setParameters([
                'id' => $id,
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }
}
