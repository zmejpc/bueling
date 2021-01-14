<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;
use Ecommerce\Entity\Project;

/**
 * ProjectRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class ProjectRepository extends DashboardRepository
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

    public function getForFrontend(int $max_results = 10)
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => Project::YES,
            ])
            ->setMaxResults($max_results);

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
                'showOnWebsite' => Project::YES,
            ])
            ->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getForHomepage()
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->andWhere('q.showOnHomepage = :showOnHomepage')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => Project::YES,
                'showOnHomepage' => Project::YES,
            ])
            ->setMaxResults(2);

        return $query->getQuery()->getResult();
    }

    public function getBySlug(string $slug)
    {
        $query = self::createQuery();
        $query
            ->andWhere('q.slug=:slug')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'slug' => $slug,
                'showOnWebsite' => Project::YES
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }
}
