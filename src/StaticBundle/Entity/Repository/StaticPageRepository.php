<?php

namespace StaticBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use StaticBundle\Entity\StaticPageInterface;
use SeoBundle\Entity\Repository\SeoRepository;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class StaticPageRepository extends DashboardRepository implements StaticPageRepositoryInterface
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't');

        $query = SeoRepository::addSeo($query);

        return $query;
    }

    /**
     * @param string $systemName
     * @return StaticPageInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStaticPageBySystemName(string $systemName): ?StaticPageInterface
    {
        $query = self::createQuery();
        $query
            ->where('q.systemName = :systemName')
            ->setParameter('systemName', $systemName);

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
