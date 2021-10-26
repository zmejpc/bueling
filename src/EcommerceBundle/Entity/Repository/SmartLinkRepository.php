<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Ecommerce\Entity\ProductCategory;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\SmartLink;

/**
 * SmartLinkRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class SmartLinkRepository extends DashboardRepository
{
    public function getForFilterByCategory(ProductCategory $category)
    {
        return $this->createQueryBuilder('q')
            ->leftJoin('q.products', 'products')
            ->leftJoin('products.categories', 'categories')
            ->where('q.showOnWebsite=:showOnWebsite')
            ->andWhere('categories=:category')
            ->orderBy('q.position')
            ->setParameters([
                'showOnWebsite' => SmartLink::YES,
                'category' => $category,
            ])
            ->getQuery()
            ->getResult();
    }
}