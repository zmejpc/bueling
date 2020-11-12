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
class ProductRepository extends DashboardRepository implements ProductRepositoryInterface
{
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

    public function getProductsByFilterQueryBuilder($filter)
    {
        $query = self::getQuery();

        if (!empty($filter['category'])) {
            $query->andWhere($query->expr()->in('categories.id', $filter['category']));
        }

        if (!empty($filter['min-price'])) {
            $query
                ->andWhere('q.price>=:min_price')
                ->setParameter('min_price', $filter['min-price']);
        }

        if (!empty($filter['max-price'])) {
            $query
                ->andWhere('q.price<=:max_price')
                ->setParameter('max_price', $filter['max-price']);
        }

        return $query
            ->addOrderBy('q.position', 'DESC')
            ->andWhere('q.price IS NOT NULL')
            ->andWhere('q.price > 0')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameter('showOnWebsite', Product::YES);
    }

    public function getCountForMenuForCategory($category) {
        return $this->createQueryBuilder('q')
            ->select('count(q.id)')
            ->leftJoin('q.categories', 'productCategory')
            ->where('productCategory.id=:categories')
            ->andWhere('q.price IS NOT NULL')
            ->andWhere('q.price > 0')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameter('showOnWebsite', Product::YES)
            ->setParameter('categories', $category->getId())
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getProductsByFilter($filter, $limit = null, $offset = null)
    {
        $query = $this->getProductsByFilterQueryBuilder($filter);

        if ($limit) {
            $query->setMaxResults($limit);
        }

        if ($offset) {
            $query->setFirstResult($offset);
        }

        return $query->getQuery()->getResult();
    }

    public function getProductsPriceRangeByFilter($filter)
    {
        $query = $this->getProductsByFilterQueryBuilder($filter)
            ->select('MIN(q.originalPrice) as lowPrice, MAX(q.originalPrice) as highPrice');

        $result = $query->getQuery()->getResult();

        return reset($result);
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

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::getQuery();

        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function findById(int $id)
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't')
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getProducts()
    {
        $query = self::getQuery();
        $query
            ->setParameters([
                'showOnWebsite' => Product::YES
            ]);

        $query = self::getParentCategoryQuery($query);

        $results = $query->getQuery()->getResult();

        return $results;
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

    public function getTopSaleProductsElements()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t, productCategory, galleryImages, status')
            ->leftJoin('q.categories', 'productCategory')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.galleryImages', 'galleryImages', 'WITH', 'galleryImages.showOnWebsite = :showOnWebsite')
            ->leftJoin('q.status', 'status')
            ->where('q.topSale =:topSale')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->orderBy('status.systemName', 'ASC')
            ->setParameters([
                'topSale' => Product::YES,
                'showOnWebsite' => Product::YES
            ])
            ->getQuery()
            ->getResult();
        return $query;
    }

    public function countByCategoryId(int $category_id)
    {
        return $this->createQueryBuilder('q')
            ->select('count(q.id)')
            ->leftJoin('q.categories', 'productCategory')
            ->andWhere('productCategory.id=:category_id')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->andWhere('q.originalPrice IS NOT NULL')
            ->andWhere('q.originalPrice > 0')
            ->setParameters([
                'category_id' => $category_id,
                'showOnWebsite' => Product::YES
            ])
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getForFrontendByCategoryId(int $category_id)
    {
        return $this->createQueryBuilder('q')
            ->select('q, t, galleryImages')
            ->join('q.translations', 't')
            ->leftJoin('q.galleryImages', 'galleryImages', 'WITH', 'galleryImages.showOnWebsite = :showOnWebsite')
            ->leftJoin('q.categories', 'categories')
            ->where('categories.id=:category_id')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->andWhere('q.price IS NOT NULL')
            ->andWhere('q.price > 0')
            ->setParameters([
                'category_id' => $category_id,
                'showOnWebsite' => Product::YES
            ])
            ->orderBy('q.position', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getPricesForFilter($filter)
    {
        $query = $this->createQueryBuilder('q')
            ->select('MIN(q.price) AS min, MAX(q.price) AS max')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->andWhere('q.price IS NOT NULL')
            ->andWhere('q.price > 0')
            ->setParameter('showOnWebsite', Product::YES);

        if(!empty($filter['category'])) {
            $query
                ->leftJoin('q.categories', 'categories')
                ->andWhere($query->expr()->in('categories.id', $filter['category']));
        }

        $result = $query->getQuery()->getScalarResult();
        $result = reset($result);

        $result = [
            'min' => floor($result['min']),
            'max' => ceil($result['max']),
        ];

        if($result['min'] == $result['max']) {
            $result['max'] += 1;
        }

        return $result;
    }
}
