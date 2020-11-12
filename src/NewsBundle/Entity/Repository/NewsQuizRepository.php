<?php

namespace NewsBundle\Entity\Repository;

use DashboardBundle\Entity\Repository\DashboardRepository;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizRepository extends DashboardRepository
{
    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->select('q, t, news, news_t,quizOptions, quizOptions_t')
            ->leftJoin('q.news', 'news')
            ->leftJoin('news.translations', 'news_t')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.quizOptions', 'quizOptions')
            ->leftJoin('quizOptions.translations', 'quizOptions_t');
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    public function getCustomElementByIdForDashboardEditOrDeleteFormAction(int $id, int $news)
    {
        $query = self::createQuery();
        $query
            ->where('q.id =:id')
            ->andWhere('q.news =:news')
            ->setParameters([
                'id' => $id,
                'news' => $news,
            ]);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @param NewsInterface $news
     * @return QueryBuilder
     */
    private function customAllElementsForIndexDashboardQueryBuilder(
        array $dataTable, array $listElementsForIndex, NewsInterface $news
    ): QueryBuilder
    {
        $tempSortElements = $dataTable['sort']['field'];
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder->select('q');
        $defined = [];

        foreach ($listElementsForIndex as $key => $item) {
            $items = explode("-", $key);
            $key = str_replace("-", "", $key);

            if (is_array($items)) {
                $field = array_pop($items);
                $iterator = 'q';

                foreach ($items as $element) {
                    $tempIterator = $key . $element;

                    if (empty($defined[$key][$element])) {
                        $queryBuilder
                            ->addSelect($tempIterator)
                            ->leftJoin($iterator . '.' . $element, $tempIterator);

                        $defined[$key][$element] = $element;
                    }

                    $iterator = $tempIterator;
                }

                if (!empty($dataTable['query']) and !empty($dataTable['query']['generalSearch'])) {
                    $queryBuilder
                        ->orWhere(
                            $queryBuilder
                                ->expr()
                                ->like('LOWER(' . $iterator . '.' . $field . ')', ':searchQuery' . $key))
                        ->setParameter('searchQuery' . $key, '%' . mb_strtolower($dataTable['query']['generalSearch'], 'UTF-8') . '%');
                }

                if (str_replace('-', '', $tempSortElements) == $key) {
                    $queryBuilder
                        ->addOrderBy($iterator . '.' . $field, $dataTable['sort']['sort']);
                }
            }
        }

        if ($tempSortElements == 'id') {
            $queryBuilder
                ->addOrderBy('q.id', $dataTable['sort']['sort']);
        }

        if (!empty($dataTable['query']) and !empty($dataTable['query']['generalSearch'])) {
            $queryBuilder
                ->orWhere(
                    $queryBuilder
                        ->expr()
                        ->like('LOWER(q.id)', ':searchQueryId')
                );
            $queryBuilder
                ->setParameter('searchQueryId', '%' . mb_strtolower($dataTable['query']['generalSearch'], 'UTF-8') . '%');
        }

        $queryBuilder
            ->andWhere('q.news =:news')
            ->setParameter('news', $news->getId());

        return $queryBuilder;
    }

    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @param NewsInterface $news
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function customCountAllElementsForIndexDashboard(
        array $dataTable, array $listElementsForIndex, NewsInterface $news
    )
    {
        return $this->customAllElementsForIndexDashboardQueryBuilder($dataTable, $listElementsForIndex, $news)
            ->select('count(q.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @param NewsInterface $news
     * @return \Doctrine\ORM\Query
     */
    public function customAllElementsForIndexDashboard(
        array $dataTable, array $listElementsForIndex, NewsInterface $news
    )
    {
        return $this
            ->customAllElementsForIndexDashboardQueryBuilder($dataTable, $listElementsForIndex, $news)
            ->getQuery();
    } 

    public function getElementsByNewsId(int $id)
    {
        $query = self::createQuery();
        $query
            ->where('news.id =:id')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->setParameters([
                'id' => $id,
                'showOnWebsite' => YesOrNoInterface::YES
            ])
            ->OrderBy('q.position', 'ASC');

        return $query->getQuery()->getResult();
    }
}