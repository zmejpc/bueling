<?php

namespace SeoBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class SeoPageRepository extends DashboardRepository implements SeoPageRepositoryInterface
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q')->select('q');

        return SeoRepository::addSeo($query);
    }

    /**
     * @param string $page
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForPageBySystemName(string $page)
    {
        $query = self::createQuery();
        $query
            ->where('q.systemName = :seo_page')
            ->setParameter('seo_page', $page);

        $result = $query->getQuery()->getOneOrNullResult();

        $seo = [];

        if ($result) {
            $seo = $result->getSeoForPage();
        }

        return $seo;
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
