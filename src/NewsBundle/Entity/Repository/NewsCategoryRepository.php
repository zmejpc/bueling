<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsCategory;
use SeoBundle\Entity\Repository\SeoRepository;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use DashboardBundle\Entity\Repository\DashboardRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class NewsCategoryRepository extends DashboardRepository implements NewsCategoryRepositoryInterface
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
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query = SeoRepository::addSeo($query);

        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @return QueryBuilder
     */
    public function getNewsCategoryForNewsForm(): QueryBuilder
    {
        $query = self::createQuery();
        $query->addOrderBy('q.position', 'ASC');

        return $query;
    }

    /**
     * @param int|null $count
     * @return QueryBuilder
     */
    private function getLimitQueryElements(int $count = null): QueryBuilder
    {
        $query = self::createQuery();
        $query
            ->leftJoin('q.news', 'news', 'WITH', 'news.showOnWebsite = :showOnWebsite AND news.publishAt <=:publishAt')
            ->addOrderBy('q.position', 'ASC')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameter('publishAt', new \DateTime('now'))
            ->setParameter('showOnWebsite', YesOrNoInterface::YES);

        if (!is_null($count)) {
            $query->setMaxResults($count);
        }

        return $query;
    }

    private function getQueryForElements(): QueryBuilder
    {
        $query = self::createQuery();
        $query
            ->addOrderBy('q.position', 'ASC')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameter('showOnWebsite', NewsCategoryInterface::YES);

        return $query;
    }

    /**
     * @return mixed
     */
    public function getElementsForMenu(): array
    {
        $query = self::getLimitQueryElements(null);

        $query
            ->andWhere('q.showInMenu =:showInMenu')
            ->setParameter('showInMenu', YesOrNoInterface::YES);

        return $query->getQuery()->getResult();
    }

    /**
     * @return mixed
     */
    public function getAsideElementsOnMain(int $count = null): array
    {
        $query = self::createQuery()
            ->addOrderBy('q.position', 'ASC')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameter('showOnWebsite', YesOrNoInterface::YES);

        if (!is_null($count)) {
            $query->setMaxResults($count);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getElementsForSiteMap(): array
    {
        return self::getLimitQueryElements(null)->getQuery()->getArrayResult();
    }

    /**
     * @return array
     */
    public function getElements(): array
    {
        return self::getLimitQueryElements(null)->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getElementsIndexByTitle(): array
    {
        $query = self::createQuery();
        $temp = $query->getQuery()->getResult();

        $results = [];
        if (!empty($temp)) {
            foreach ($temp as $item) {
                $results[$item->translate()->getTitle()] = $item;
            }
        }

        return $results;
    }

    /**
     * @param string $slug
     * @return NewsCategory|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementBySlug(string $slug): ?NewsCategory
    {
        $query = self::createQuery();

        $query
            ->where('t.slug =:slug')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameters([
                'showOnWebsite' => YesOrNoInterface::YES,
                'slug' => $slug
            ])
            ->setMaxResults(1);

        $tempResults = $query->getQuery()->getOneOrNullResult();

        if (!is_null($tempResults)) {
            $query2 = self::createQuery();
            $query2 = SeoRepository::addSeo($query2);

            $query2
                ->where('q.id =:id')
                ->setParameter('id', $tempResults->getId());

            return $query2->getQuery()->getOneOrNullResult();
        }

        return $tempResults;
    }
}
