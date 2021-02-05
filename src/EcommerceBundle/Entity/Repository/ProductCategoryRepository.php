<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\ProductCategory;

/**
 * ProductCategoryRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class ProductCategoryRepository extends DashboardRepository
{
    private function createQuery()
    {
        $query = $this->createQueryBuilder('q')
            // ->select('q, t')
            ->leftJoin('q.translations', 't');

        return $query;
    }

    private function addSeoQuery(QueryBuilder $query){
        $query
            ->addSelect('seo, seo_t')
            ->leftJoin('q.seo', 'seo')
            ->leftJoin('seo.translations', 'seo_t');

        return $query;
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query = self::addSeoQuery($query);

        $query
            ->where('q.id =:id')
            ->setParameters([
                'id' => $id,
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getProductCategoryById(int $id)
    {
        $query = self::createQuery();
        $query = self::addSeoQuery($query);

        $query
            ->andWhere('q.id = :id')
            ->andWhere('q.showOnWebsite = :showOnWebsite')
            ->setParameters([
                'id' => $id,
                'showOnWebsite' => ProductCategory::YES
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getForFrontendMenu()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ProductCategory::YES,
            ]);

        return $query->getQuery()->getResult();
    }

    public function getForFrontend(int $max_results = 10)
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ProductCategory::YES,
            ]);

        return $query
            ->setMaxResults($max_results)
            ->getQuery()
            ->getResult();
    }

    public function getOneForFrontend()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere('q.poster IS NOT NULL')
            ->orderBy('RAND()')
            ->setParameters([
                'showOnWebsite' => ProductCategory::YES,
            ])
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getProductCategoryBySlug($slug)
    {
        $query = self::createQuery();
        $query = self::addSeoQuery($query);

        $query
            ->andWhere('t.slug =:slug')
            ->andWhere('q.showOnWebsite = :showOnWebsite')
            ->setParameters([
                'slug' => $slug,
                'showOnWebsite' => ProductCategory::YES,
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getCategoriesForProductForm()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->orderBy('q.position', 'ASC');

        return $query;
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

    public function getForFilter($filter)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.products', 'products', 'WITH', 'products.showOnWebsite = :showOnWebsite AND products.price IS NOT NULL AND products.price > 0')
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameter('showOnWebsite', ProductCategory::YES);

        if(!empty($filter['capsule'])) {
            $query
                ->leftJoin('products.capsules', 'capsule')
                ->andWhere('capsule=:capsule')
                ->setParameter('capsule', $filter['capsule']);
        }

        if(!empty($filter['size'])) {
            $query
                ->leftJoin('products.sizes', 'sizes')
                ->leftJoin('sizes.size', 'size')
                ->andWhere($query->expr()->in('size.id', $filter['size']));
        }

        if (!empty($filter['min-price'])) {
            $query
                ->andWhere('products.price>=:min_price')
                ->setParameter('min_price', $filter['min-price']);
        }

        if (!empty($filter['max-price'])) {
            $query
                ->andWhere('products.price<=:max_price')
                ->setParameter('max_price', $filter['max-price']);
        }

        return $query->getQuery()->getResult();
    }
}
