<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Ecommerce\Entity\ActivityArea;
use BackendBundle\Entity\Region;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Ecommerce\Entity\Project;
use Ecommerce\Entity\Product;

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

    public function getForFrontend(int $max_results = 10, ActivityArea $activityArea = null, Region $region = null)
    {
        $query = self::createQuery();
        
        $query
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.publishAt', 'DESC')
            ->setParameters([
                'showOnWebsite' => Project::YES,
            ])
            ->setMaxResults($max_results);

        if ($activityArea) {
            $query
                ->leftJoin('q.activityAreas', 'activityAreas')
                ->andWhere($query->expr()->in('activityAreas', [$activityArea->getId()]));
        }

        if ($region) {
            $query
                ->leftJoin('q.region', 'region')
                ->andWhere('region=:region')
                ->setParameter('region', $region->getId());
        }

        return $query->getQuery()->getResult();
    }

    public function getNeighborForFrontend(Project $project, string $dir = 'next')
    {
        $query = self::createQuery();

        if ($dir == 'next') {
            $query->where('q.id > :id')
            ->orderBy('q.id', 'ASC');
        } else {
            $query->where('q.id < :id')
            ->orderBy('q.id', 'DESC');
        }

        return $query
            ->andWhere('q.showOnWebsite = :showOnWebsite')
            ->setParameters([
                'id' => $project->getId(),
                'showOnWebsite' => Project::YES,
            ])
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getRelatedForFrontend(Project $project, int $limit = 2)
    {
        $query = $this->createQueryBuilder('q')
            ->leftJoin('q.translations', 't')
            ->where('q.showOnWebsite=:showOnWebsite')
            ->andWhere('q.id<>:id')
            ->orderBy('RAND()')
            ->setParameters([
                'id' => $project->getId(),
                'showOnWebsite' => Project::YES,
            ]);

        $originQuery = clone $query;

        $productsIds = array_map(function($el) {
            return $el->getId();
        }, $project->getProducts()->toArray());

        if ($productsIds) {
            $query
                ->leftJoin('q.products', 'products')
                ->andWhere($query->expr()->in('products', $productsIds));
        }

        $query
            ->setMaxResults($limit)
            ->getQuery();

        $projects = (new Paginator($query))->getIterator()->getArrayCopy();

        if (sizeof($projects) < $limit) {
            $originQuery
                ->setMaxResults($limit - sizeof($projects));

            $projectsIds = array_map(function($el) {
                return $el->getId();
            }, $projects);

            if ($projectsIds) {
                $originQuery
                    ->andWhere($query->expr()->notIn('q.id', $projectsIds));
            }

            $originQuery
                ->getQuery();

            $projects = array_merge($projects, (new Paginator($originQuery))->getIterator()->getArrayCopy());
        }

        return $projects;
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
            ->orderBy('RAND()')
            ->setMaxResults(2)
            ->setParameters([
                'showOnWebsite' => Project::YES,
                'showOnHomepage' => Project::YES,
            ])
            ->getQuery();

        return new Paginator($query);
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

    public function getYearsForFilter()
    {
        return $this->createQueryBuilder('q')
            ->select("DATE_FORMAT(q.publishAt, '%Y')")
            ->distinct()
            ->where('q.showOnWebsite=:showOnWebsite')
            ->setParameter('showOnWebsite', Project::YES)
            ->getQuery()
            ->getScalarResult();

    }

    public function getRelatedItemDataForFilter(string $related_item, string $locale)
    {
        return $this->createQueryBuilder('q')
            ->select('partial q.{id}, partial '.$related_item.'.{id}, partial '.$related_item.'_t.{id, title}')
            ->join('q.'.$related_item, $related_item, 'WITH', $related_item.'.showOnWebsite=:showOnWebsite')
            ->join($related_item.'.translations', $related_item.'_t', 'WITH', $related_item.'_t.locale=:locale')
            ->where('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'showOnWebsite' => Project::YES,
                'locale' => $locale,
            ])
            ->groupBy($related_item)
            ->getQuery()
            ->getArrayResult();
    }

    public function getRegionDataForFilter(string $locale)
    {
        return $this->createQueryBuilder('q')
            ->select('partial q.{id}, partial region.{id}, partial region_t.{id, title}')
            ->join('q.region', 'region')
            ->join('region.translations', 'region_t', 'WITH', 'region_t.locale=:locale')
            ->setParameters([
                'locale' => $locale,
            ])
            ->groupBy('region')
            ->getQuery()
            ->getArrayResult();
    }

    public function getCategoryDataForFilter(string $locale)
    {
        return $this->createQueryBuilder('q')
            ->select('partial q.{id}, partial products.{id}, partial categories.{id}, partial categories_t.{id, title}')
            ->join('q.products', 'products', 'WITH', 'products.showOnWebsite=:showOnWebsite')
            ->join('products.categories', 'categories', 'WITH', 'categories.showOnWebsite=:showOnWebsite')
            ->join('categories.translations','categories_t', 'WITH', 'categories_t.locale=:locale')
            ->where('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'showOnWebsite' => Project::YES,
                'locale' => $locale,
            ])
            ->groupBy('categories')
            ->getQuery()
            ->getArrayResult();
    }

    public function getByFilter(array $filter, bool $count = false)
    {
        $query = self::createQuery()
            ->where('q.showOnWebsite = :showOnWebsite')
            ->setParameter('showOnWebsite', Project::YES);

        if (!empty($filter['years'])) {
            $query
                ->andWhere('q.publishAt > :year')
                ->andWhere('q.publishAt < :next_year')
                ->setParameter('year', (\DateTime::createFromFormat('Y', $filter['years']))->format('Y-01-01'))
                ->setParameter('next_year', (\DateTime::createFromFormat('Y', $filter['years']+1))->format('Y-01-01'));
        }

        if (!empty($filter['applicationFields'])) {
            $query
                ->leftJoin('q.applicationFields', 'applicationFields')
                ->andWhere('applicationFields=:applicationFields')
                ->setParameter('applicationFields', $filter['applicationFields']);
        }

        if (!empty($filter['activityAreas'])) {
            $query
                ->leftJoin('q.activityAreas', 'activityAreas')
                ->andWhere('activityAreas=:activityAreas')
                ->setParameter('activityAreas', $filter['activityAreas']);
        }

        if (!empty($filter['technicTypes'])) {
            $query
                ->leftJoin('q.technicTypes', 'technicTypes')
                ->andWhere('technicTypes=:technicTypes')
                ->setParameter('technicTypes', $filter['technicTypes']);
        }

        if (!empty($filter['region'])) {
            $query
                ->leftJoin('q.region', 'region')
                ->andWhere('region=:region')
                ->setParameter('region', $filter['region']);
        }

        if (!empty($filter['products'])) {
            $query
                ->leftJoin('q.products', 'products')
                ->andWhere('products=:products')
                ->setParameter('products', $filter['products']);
        }

        if (!empty($filter['categories'])) {

            if (empty($filter['products'])) {
                $query
                    ->leftJoin('q.products', 'products');
            }

            $query
                ->join('products.categories', 'categories')
                ->andWhere('categories=:categories')
                ->setParameter('categories', $filter['categories']);
        }

        if ($count) {
            return $query
                ->select('count(distinct q.id)')
                ->getQuery()
                ->getSingleScalarResult();
        }

        return $query
            ->orderBy('q.publishAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function getRelatedByProduct(Product $product)
    {
        $query = self::createQuery()
            ->join('q.products', 'products', 'WITH', 'products.showOnWebsite=:showOnWebsite')
            ->where('q.showOnWebsite=:showOnWebsite')
            ->andWhere('products=:product')
            ->setParameters([
                'product' => $product,
                'showOnWebsite' => Project::YES
            ])
            ->orderBy('RAND()')
            ->getQuery()
            ->setMaxResults(1);

        $result = (new Paginator($query))->getIterator()->getArrayCopy();

        return reset($result);
    }
}
