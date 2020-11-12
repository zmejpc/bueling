<?php

namespace StaticBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class StaticContentRepository extends DashboardRepository implements StaticContentRepositoryInterface
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
     * @param string $page
     * @return array
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getByPage(string $page): array
    {
        $query = self::createQuery();
        $query
            ->indexBy('q', 'q.linkName')
            ->where('q.page =:page')
            ->setParameter('page', $page);

        return $query->getQuery()->getResult();
    }


    public function getByPageAndBlock(string $page,string $block)
    {
        $query = self::createQuery();
        $query
            ->indexBy('q', 'q.linkName')
            ->where('q.page =:page')
            ->andWhere('q.linkName =:block')
            ->setParameter('page', $page)
            ->setParameter('block', $block);

        return $query->getQuery()->getOneOrNullResult();
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
