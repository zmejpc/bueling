<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Ecommerce\Entity\ApplicationField;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\SmartLink;
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
        $slug = array_reverse(explode('/', $slug));
        $slug = reset($slug);
        
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

    public function getOneForFrontend()
    {
        $query = self::getQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('RAND()')
            ->setParameters([
                'showOnWebsite' => Product::YES,
            ])
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getForFrontend(ProductCategory $category, SmartLink $smartLink = null)
    {
        $query = self::getQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere($query->expr()->in('categories', [$category->getId()]))
            ->orderBy('q.position')
            ->setParameters([
                'showOnWebsite' => Product::YES,
            ]);

        if ($smartLink) {
            $query
                ->leftJoin('q.smartLinks', 'smartLinks')
                ->andWhere($query->expr()->in('smartLinks', [$smartLink->getId()]));
        }

        return $query->getQuery()->getResult();
    }

    public function getForFrontendByApplicationField(ApplicationField $applicationField)
    {
        return self::getQuery()
            ->leftJoin('q.applicationFields', 'applicationFields')
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere('applicationFields=:applicationField')
            ->orderBy('q.position')
            ->setParameters([
                'applicationField' => $applicationField,
                'showOnWebsite' => Product::YES,
            ])
            ->getQuery()
            ->getResult();
    }
}
