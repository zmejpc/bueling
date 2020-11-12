<?php

namespace SeoBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class SeoRepository
{
    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    public static function addSeo(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addSelect('seo, seo_t')
            ->leftJoin('q.seo', 'seo')
            ->leftJoin('seo.translations', 'seo_t');

        return $query;
    }
}
