<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\ActivityArea;

/**
 * ActivityAreaRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class ActivityAreaRepository extends DashboardRepository
{
    private function createQuery()
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, t')
            ->leftJoin('q.translations', 't');

        return $query;
    }

    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();

        $query
            ->where('q.id =:id')
            ->setParameters([
                'id' => $id,
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
                'showOnWebsite' => ActivityArea::YES,
            ]);

        return $query->getQuery()->getResult();
    }

    public function getForHomepage()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere('q.showOnHomepage = :showOnHomepage')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ActivityArea::YES,
                'showOnHomepage' => ActivityArea::YES,
            ])
            ->setMaxResults(4);

        return $query->getQuery()->getResult();
    }

    public function getForFrontend(int $max_results = 10)
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ActivityArea::YES,
            ])
            ->setMaxResults($max_results);

        return $query->getQuery()->getResult();
    }

    public function getForFilter()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere('q.showInFilter = :showInFilter')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ActivityArea::YES,
                'showInFilter' => ActivityArea::YES,
            ]);

        return $query->getQuery()->getResult();
    }

    public function getOneForFrontend()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->join('q.galleryImages', 'galleryImages', 'WITH', 'galleryImages.showOnWebsite=:showOnWebsite')
            ->orderBy('RAND()')
            ->setParameters([
                'showOnWebsite' => ActivityArea::YES,
            ])
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getBySlug(string $slug)
    {
        $query = self::createQuery();
        $query
            ->andWhere('q.slug=:slug')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'slug' => $slug,
                'showOnWebsite' => ActivityArea::YES
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }
}
