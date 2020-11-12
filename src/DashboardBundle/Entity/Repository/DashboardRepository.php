<?php

namespace DashboardBundle\Entity\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * @author Design studio origami <https://origami.ua>
 */
abstract class DashboardRepository extends EntityRepository implements DashboardRepositoryInterface
{
    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @return QueryBuilder
     */
    protected function allElementsForIndexDashboardQueryBuilder(array $dataTable, array $listElementsForIndex): QueryBuilder
    {
        $tempSortElements = $dataTable['sort']['field'];
        $queryBuilder = $this->createQueryBuilder('q');
        $queryBuilder->select('q');
        $defined = [];
        $listElementsForIndex += ['id' => ''];

        foreach ($listElementsForIndex as $key => $item) {
            if (!isset($item['exist'])) {
                $items = explode("-", $key);
                $key = str_replace("-", "", $key);

                if (is_array($items)) {
                    $field = array_pop($items);
                    if(strpos($key, '_alias_') !== false) {
                        $aliasData = explode('_alias_', $key);
                        
                        if($aliasData[0] == 'notinsearch')
                            continue;

                        $field = $aliasData[1];
                    }
                    $iterator = 'q';

                    foreach ($items as $element) {
                        $tempIterator = $iterator . $element;
                        if (empty($defined[$iterator][$element])) {
                            $queryBuilder
                                ->addSelect($tempIterator)
                                ->leftJoin($iterator . '.' . $element, $tempIterator);

                            $defined[$iterator][$element] = $element;
                        }

                        $iterator = $tempIterator;
                    }

                    if (!empty($dataTable['query'])) {
                        if(!empty($dataTable['query']['generalSearch'])) {
                            $queryBuilder
                                ->orWhere(
                                    $queryBuilder
                                        ->expr()
                                        ->like('LOWER(' . $iterator . '.' . $field . ')', ':searchQuery' . $key))
                                ->setParameter('searchQuery' . $key, '%' . mb_strtolower($dataTable['query']['generalSearch'], 'UTF-8') . '%');
                        }

                        if(!empty($dataTable['query']['filter'])) {
                            foreach($dataTable['query']['filter'] as $filterVield => $filterValue) {
                                if($filterValue != '' && $key == str_replace("-", "", $filterVield)) {
                                    if(is_array($filterValue)) {
                                        $queryBuilder
                                            ->andWhere($queryBuilder->expr()->in($iterator . '.' . $field, $filterValue));
                                    } else {
                                        $queryBuilder
                                            ->andWhere($iterator . '.' . $field . '=:filterValue' . $key)
                                            ->setParameter('filterValue' . $key, $filterValue);
                                    }
                                }
                            }
                        }
                    }

                    if (str_replace('-', '', $tempSortElements) == $key) {
                        $queryBuilder
                            ->addOrderBy($iterator . '.' . $field, $dataTable['sort']['sort']);
                    }
                }
            }
        }

        if ($tempSortElements == 'id') {
            $queryBuilder->addOrderBy('q.id', $dataTable['sort']['sort']);
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

        // search filter by keywords
//        $filter = isset($datatable['query']['generalSearch']) && is_string($dataTable['query']['generalSearch']) ? $dataTable['query']['generalSearch'] : '';
//        if (!empty($filter)) {
////            $data = array_filter($data, function ($a) use ($filter) {
////                return (boolean)preg_grep("/$filter/i", (array)$a);
////            });
////            unset($dataTable['query']['generalSearch']);
//        }

        // filter by field query
//        $query = isset($datatable['query']) && is_array($dataTable['query']) ? $dataTable['query'] : null;
//        if (is_array($query)) {
////            $query = array_filter($query);
////            foreach ($query as $key => $val) {
////                $data = list_filter($data, array($key => $val));
////            }
//        }

        return $queryBuilder;
    }

    /**
     * @param array $dataTable
     * @param array $listElementsForIndex
     * @return \Doctrine\ORM\Query|mixed
     */
    public function allElementsForIndexDashboard(array $dataTable, array $listElementsForIndex): Query
    {
        return $this->allElementsForIndexDashboardQueryBuilder($dataTable, $listElementsForIndex)->getQuery();
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        return $this->createQueryBuilder('q')
            ->where('q.id =:id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
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

    public function deleteByIds(array $ids)
    {
        $this->createQueryBuilder('q')
            ->delete()
            ->where('q.id IN (:ids)')
            ->setParameters(['ids' => $ids])
            ->getQuery()
            ->execute();
    }

    public function deleteAll()
    {
        $this->createQueryBuilder('q')
            ->delete()
            ->getQuery()
            ->execute();
    }
}