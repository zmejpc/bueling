<?php

namespace ComponentBundle\Utils;

use Doctrine\ORM\Query;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @author Design studio origami <https://origami.ua>
 */
class SearchManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * SearchManager constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string|null $searchQuery
     * @param array $entitiesForSearch
     * @param int|null $maxResults
     * @param bool $isOnlyQuery
     * @return array
     */
    public function searchResultsOnlyInTitleForAllEntities(
        array $entitiesForSearch, string $searchQuery = null,
        int $maxResults = null, bool $isOnlyQuery = false
    )
    {
        if (count($entitiesForSearch) > 1) {
            $isOnlyQuery = false;
        }

        $result = [];
        foreach ($entitiesForSearch as $key => $searchType) {
            if (count($entitiesForSearch) == 1) {
                $result = self::helperForSearchResultsOnlyInTitle(
                    $searchType, $searchQuery, $maxResults, $isOnlyQuery
                );
            } else {
                $result = array_merge($result, self::helperForSearchResultsOnlyInTitle(
                    $searchType, $searchQuery, $maxResults, $isOnlyQuery
                ));
            }
        }

        return $result;
    }

    /**
     * @param string|null $searchQuery
     * @param int|null $maxResults
     * @param array $item
     * @param bool $isOnlyQuery
     * @return array|Query
     */
    protected function helperForSearchResultsOnlyInTitle(
        array $item, string $searchQuery = null, int $maxResults = null, bool $isOnlyQuery = false
    )
    {
        $result = [];

        $this->em->clear();

        if (!is_null($item['entity'])) {
            $query = $this->em->createQueryBuilder()
                ->select('q')
                ->from($item['entity'], 'q');

            if (!empty($searchQuery)) {
                foreach ($item['fields'] as $field) {
                    if (!empty($field['is_translate']) and !empty($field['field']) and $field['is_translate'] == true) {
                        $query
                            ->addSelect('t')
                            ->leftJoin('q.translations', 't')
                            ->orWhere(
                                $query
                                    ->expr()
                                    ->like('LOWER(' . 't.' . $field['field'] . ')', ':searchQuery')
                            );
                    } elseif (!empty($field['field'])) {
                        $query
                            ->orWhere(
                                $query
                                    ->expr()
                                    ->like('LOWER(' . 'q.' . $field['field'] . ')', ':searchQuery')
                            );
                    }
                }

                $query->setParameter('searchQuery', '%' . mb_strtolower($searchQuery, 'UTF-8') . '%');
            }

            if (!empty($item['sort'])) {
                $query->orderBy('q.' . $item['sort']['field'], $item['sort']['sort']);
            }

            if (!is_null($maxResults)) {
                $query
                    ->setFirstResult(0)
                    ->setMaxResults($maxResults);
            }

            $resultSearch = new Paginator($query, $fetchJoinCollection = true);

            $ids = [];

            foreach ($resultSearch as $searchResult) {
                $ids[] = $searchResult->getId();
            }

            $this->em->clear();
            $temp = $this->em->getRepository($item['entity'])->getByIdsForSearch($ids, $isOnlyQuery);

            if (!$temp instanceof Query) {
                $result = array_merge($result, $temp);
            } else {
                $result = $temp;
            }
        }

        return $result;
    }

    /**
     * @param string|null $searchQuery
     * @param string|null $searchType
     * @param array $entitiesForSearch
     * @param int|null $maxResults
     * @param bool $isOnlyQuery
     * @return array|Query
     */
    public function searchResultsOnlyInTitle(
        array $entitiesForSearch, string $searchQuery = null, string $searchType = null,
        int $maxResults = null, bool $isOnlyQuery = false
    )
    {
        $result = [];

        if (is_null($searchType)) {
            $result = self::searchResultsOnlyInTitleForAllEntities(
                $entitiesForSearch, $searchQuery, $maxResults, $isOnlyQuery
            );
        } elseif (!empty($entitiesForSearch[$searchType])) {
            $item = $entitiesForSearch[$searchType];
            $result = self::helperForSearchResultsOnlyInTitle($item, $searchQuery, $maxResults, $isOnlyQuery);
        }

        return $result;
    }
}