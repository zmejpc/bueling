<?php

namespace Ecommerce\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use Ecommerce\Entity\ApplicationField;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\AbstractQuery;

/**
 * ApplicationFieldRepository
 *
 * @author Design studio origami <https://origami.ua>
 */
class ApplicationFieldRepository extends DashboardRepository
{
	public function getForFrontend()
    {
        return $this->createQueryBuilder('q')
            ->select('q, t')
            ->join('q.translations', 't')
            ->where('q.showOnWebsite = :showOnWebsite')
            ->orderBy('q.position', 'ASC')
            ->setParameters([
                'showOnWebsite' => ApplicationField::YES,
            ])
            ->getQuery()
            ->getResult();
    }

    public function getBySlug(string $slug)
    {
        return $this->createQueryBuilder('q')
            ->select('q, t, seo')
            ->join('q.seo', 'seo')
            ->join('q.translations', 't')
            ->where('t.slug=:slug')
            ->andWhere('q.showOnWebsite=:showOnWebsite')
            ->setParameters([
                'slug' => $slug,
                'showOnWebsite' => ApplicationField::YES
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}