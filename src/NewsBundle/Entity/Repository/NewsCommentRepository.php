<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsComment;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use DashboardBundle\Entity\Repository\DashboardRepository;

class NewsCommentRepository extends DashboardRepository
{
    /**
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        $query = $this->createQueryBuilder('q')
            ->select('q, n')
            ->leftJoin('q.news', 'n');

        return $query;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementsByNewsIdForFrontend(int $news_id)
    {
        $query = self::createQuery();
        $query
            ->where('n.id =:id')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameters([
                'id' => $news_id,
                'showOnWebsite' => YesOrNoInterface::YES
            ]);

        return $query->getQuery()->getResult();
    }

    /**
     * @return array
     */
    public function getHiddenElementsCnt(): int
    {
        $query = self::createQuery();

        $query
            ->select('count(q.id)')
            ->Where('q.showOnWebsite =:showOnWebsite')
            ->setParameter('showOnWebsite', YesOrNoInterface::NO)
            ->setMaxResults(1);

        return $query->getQuery()->getSingleScalarResult();
    }
}