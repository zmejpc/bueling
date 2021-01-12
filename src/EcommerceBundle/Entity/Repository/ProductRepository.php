<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\Product;

/**
 * @author Design studio origami <https://origami.ua>
 */
class ProductRepository extends DashboardRepository
{
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::getQuery();

        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findByIds(array $ids)
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q')
            ->where('q.id IN (:ids)')
            ->setParameters(['ids' => $ids])
            ->getQuery()
            ->getResult();

        return $qb;
    }

    private function getQuery()
    {
        $query = $this->createQueryBuilder('q')
            ->groupBy('q')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.seo', 'seo')
            ->leftJoin('seo.translations', 'seo_t')
            ->leftJoin('q.categories', 'categories')
            ->leftJoin('categories.translations', 'categories_t')
            ->leftJoin('q.galleryImages', 'galleryImages');

        return $query;
    }

    public function getProductBySlug(string $slug)
    {
        $query = self::getQuery();
        $query
            ->andWhere('q.slug=:slug')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'slug' => $slug,
                'showOnWebsite' => Product::YES
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }
}
