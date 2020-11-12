<?php

namespace NewsBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;

class NewsQuizOptionRepository extends DashboardRepository
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->select('q')
            ->leftJoin('q.translations', 't');
    }

    public function getElementById(int $id)
    {
        $query = self::createQuery();
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}