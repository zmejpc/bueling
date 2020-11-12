<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * NewsTagRepository
 */
class NewsTagRepository extends DashboardRepository
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't');
    }

    /**
     * @return QueryBuilder
     */
    public function getTagsForForm(): QueryBuilder
    {
        $query = self::createQuery();
        $query->addOrderBy('q.position', 'ASC');

        return $query;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }
}
