<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\Query;
use NewsBundle\Entity\News;
use NewsBundle\Entity\NewsInterface;
use NewsBundle\Entity\NewsCategoryInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsRepositoryInterface
{
//    /**
//     * @return array
//     */
//    public function getElementsIndexByTitle(): array;
//
//    /**
//     * @param int|null $count
//     * @return Query
//     * @throws \Exception
//     */
//    public function getQueryForLimitElements(int $count = null): Query;
//
//    /**
//     * @param int|null $count
//     * @return array
//     * @throws \Exception
//     */
//    public function getLimitElements(int $count = null): array;
//
//    /**
//     * @param NewsCategoryInterface|null $newsCategory
//     * @return array
//     * @throws \Exception
//     */
//    public function getMonthAndYearInterval(NewsCategoryInterface $newsCategory = null): array;
//
//    /**
//     * @param string $category
//     * @return array
//     * @throws \Exception
//     */
//    public function getElementsByCategorySlug(string $category): array;
//
//    /**
//     * @param string $category
//     * @param int|null $limit
//     * @return array
//     * @throws \Exception
//     */
//    public function getElementsByCategoryLimit(string $category, int $limit = null): array;
//
//    /**
//     * @return array
//     * @throws \Exception
//     */
//    public function getElementsForSiteMap(): array;
//
//    /**
//     * @param array $ids
//     * @param bool $isOnlyQuery
//     * @return Query|mixed
//     * @throws \Exception
//     */
//    public function getByIdsForSearch(array $ids, bool $isOnlyQuery);
//
//    /**
//     * @param int|null $count
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getLimitRANDElements(int $count = null);
//
//    /**
//     * @param string $slug
//     * @return NewsInterface|null
//     * @throws \Exception
//     */
//    public function getElementBySlug(string $slug): ?NewsInterface;
//
//    /**
//     * @param string $slug
//     * @param string|null $categorySlug
//     * @return News|null
//     * @throws \Doctrine\ORM\NonUniqueResultException
//     * @throws \Exception
//     */
//    public function getElementBySlugAndCategory(string $slug, string $categorySlug = null): ?News;
//
//    /**
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getElementsByInterval(int $month, int $year, int $day = null): array;
//
//    /**
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return Query
//     * @throws \Exception
//     */
//    public function getQueryElementsByInterval(int $month, int $year, int $day = null): Query;
//
//    /**
//     * @param string $categorySlug
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getElementsByCategoryAndInterval(
//        string $categorySlug, int $month, int $year, int $day = null
//    ): array;
//
//    /**
//     * @param string $categorySlug
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return Query
//     * @throws \Exception
//     */
//    public function getQueryElementsByCategoryAndInterval(
//        string $categorySlug, int $month, int $year, int $day = null
//    ): Query;
//
//    /**
//     * @param string $slug
//     * @param string $categorySlug
//     * @param int $count
//     * @return array
//     * @throws \Exception
//     */
//    public function getSimilarNews(string $slug, string $categorySlug, int $count = 3): array;
//
//    /**
//     * @param string $authorSlug
//     * @return array
//     */
//    public function getElementsByAuthor(string $authorSlug): array;
}