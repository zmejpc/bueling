<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\Query;
use NewsBundle\Entity\News;
use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsInterface;
use NewsBundle\Entity\NewsAuthorInterface;
use NewsBundle\Entity\NewsCategoryInterface;
use SeoBundle\Entity\Repository\SeoRepository;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use DashboardBundle\Entity\Repository\DashboardRepository;
use NewsBundle\Entity\NewsGalleryImageInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class NewsRepository extends DashboardRepository implements NewsRepositoryInterface
{
    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id)
    {
        $query = self::createQuery();
        $query = SeoRepository::addSeo($query);
        $query = self::addGalleryImage($query);
        $query
            ->addSelect('newsAuthor, newsAuthor_t')
            ->leftJoin('q.newsAuthor', 'newsAuthor')
            ->leftJoin('newsAuthor.translations', 'newsAuthor_t')
            ->addSelect('elements, elements_t')
            ->leftJoin('q.elements', 'elements')
            ->leftJoin('elements.translations', 'elements_t')
            ->addSelect('tags, tags_t')
            ->leftJoin('q.tags', 'tags')
            ->leftJoin('tags.translations', 'tags_t')
            ->where('q.id =:id')
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Общая часть запроса для всех других запросов
     * @return QueryBuilder
     */
    private function createQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('q')
            ->addSelect('newsCategory')
            ->leftJoin('q.translations', 't')
            ->leftJoin('q.newsCategory', 'newsCategory')
            ->leftJoin('newsCategory.translations', 'newsCategory_t');
    }

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getNewsGalleryImagesByNewsId(int $id)
    {
        return $this->createQueryBuilder('q')
            ->select('q, galleryImages')
            ->andWhere('q.id =:id')
            ->leftJoin('q.galleryImages', 'galleryImages', 'WITH', 'galleryImages.showOnWebsite =:showOnWebsite')
            ->addOrderBy('galleryImages.position', 'ASC')
            ->setParameters([
                'id' => $id,
                'showOnWebsite' => NewsGalleryImageInterface::YES
            ])->getQuery()->getOneOrNullResult();
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function addGalleryImage(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addSelect('galleryImages')
            ->leftJoin('q.galleryImages', 'galleryImages');

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     * @throws \Exception
     */
    private function helperForShowOnWebsite(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addOrderBy('q.publishAt', 'DESC')
            ->addOrderBy('q.position', 'DESC')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->andWhere('q.publishAt <=:publishAt')
            ->setParameter('publishAt', new \DateTime('now'))
            ->setParameter('showOnWebsite', YesOrNoInterface::YES);

        $query = $this->addNewsAuthor($query);

        return $query;
    }

    /**
     * @param string $category
     * @return QueryBuilder
     * @throws \Exception
     */
    private function getQueryBuilderForElementsByCategory(NewsCategoryInterface $category): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
            ->andWhere('newsCategory.id =:categoryId')
            ->setParameter('categoryId', $category->getId());

        return $query;
    }

    /**
     * @param NewsCategoryInterface $category
     * @return Query
     * @throws \Exception
     */
    public function getQueryForElementsByCategory(NewsCategoryInterface $category): Query
    {
        return self::getQueryBuilderForElementsByCategory($category)->getQuery();
    }

    /**
     * @param NewsCategoryInterface $category
     * @param NewsInterface|null $news
     * @return Query
     * @throws \Exception
     */
    public function getElementsByCategoryAndNotThisNewsWithLimit(
        NewsCategoryInterface $category, NewsInterface $news = null
    ): Query
    {
        $query = self::getQueryBuilderForElementsByCategory($category);

        if (!is_null($news)) {
            $query
                ->andWhere('q.id !=:id')
                ->setParameter('id', $news->getId());
        }

        return $query->getQuery();
    }

    /**
     * @param string $slug
     * @return QueryBuilder
     * @throws \Exception
     */
    private function getQueryForElementBySlug(string $slug): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
            ->andWhere('t.slug =:slug')
            ->setMaxResults(1)
            ->setParameter('slug', $slug);

        return $query;
    }

    /**
     * @param string $slug
     * @return News|null
     * @throws \Exception
     */
    public function getElementBySlug(string $slug): ?News
    {
        $tempResults = self::getQueryForElementBySlug($slug)->getQuery()->getOneOrNullResult();

        if (!is_null($tempResults)) {
            $query = self::createQuery();
            $query = self::helperForElement($query);
            $query
                ->andWhere('q.id =:id')
                ->setParameter('id', $tempResults->getId())
                ->setParameter('showOnWebsite', YesOrNoInterface::YES);

            return $query->getQuery()->getOneOrNullResult();
        }

        return $tempResults;
    }


    /**
     * @param int|null $count
     * @return Query
     * @throws \Exception
     */
    public function getQueryBuilderForLimitElements(int $count = null): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);

        if (!is_null($count)) {
            $query->setMaxResults($count);
        }

        return $query;
    }

    /**
     * @param int|null $count
     * @return Query
     * @throws \Exception
     */
    public function getQueryForLimitElements(int $count = null): Query
    {
        return self::getQueryBuilderForLimitElements($count)->getQuery();
    }

    /**
     * @param int|null $count
     * @return Query
     * @throws \Exception
     */
    public function getQueryBuilderForLimitElementsIsNotMain(int $count = null): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
        ->andWhere('q.poster IS NOT NULL')
        ->andWhere('q.isMain =:isMain')
        ->setParameter('isMain', YesOrNoInterface::NO);

        if (!is_null($count)) {
            $query->setMaxResults($count);
        }

        return $query;
    }

    /**
     * @param int|null $count
     * @return Query
     * @throws \Exception
     */
    public function getQueryForLimitElementsIsNotMain(int $count = null): Query
    {
        return
            self::getQueryBuilderForLimitElementsIsNotMain($count)
                ->getQuery();
    }

    /**
     * @param int|null $count
     * @return array
     * @throws \Exception
     */
    public function getLimitElementsIsNotMain(int $count = null): array
    {
        return self::getQueryForLimitElementsIsNotMain($count)->getResult();
    }

    /**
     * @param int|null $count
     * @return array
     * @throws \Exception
     */
    public function getLimitElements(int $count = null): array
    {
        return self::getQueryForLimitElements($count)->getResult();
    }

    public function getForFrontend(int $category_id = 0)
    {   
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);

        if($category_id) {
            $query
            ->andWhere('newsCategory.id =:category_id')
            ->setParameter('category_id', $category_id);
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int|null $count
     * @return array|mixed
     * @throws \Exception
     */
    public function getMainNews(int $count = null)
    {
        $query = self::getQueryBuilderForLimitElements($count);

        $query
            ->andWhere('q.isMain =:isMain')
            ->setParameter('isMain', YesOrNoInterface::YES);

        $mainNews = $query->getQuery()->getResult();

        // if (empty($mainNews)) {
        //     return self::getLimitElements($count);
        // }

        return $mainNews;
    }

    /**
     * @param array $ids
     * @param bool $isOnlyQuery
     * @return Query|mixed
     * @throws \Exception
     */
    public function getByIdsForSearch(array $ids, bool $isOnlyQuery)
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
            ->andWhere('q.id IN (:ids)')
            ->setParameter('ids', $ids);

        $results = $query->getQuery();

        if (!$isOnlyQuery) {
            $results = $results->getResult();
        }

        return $results;
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function helperForElement(QueryBuilder $query): QueryBuilder
    {
        $query = $this->addGalleryImages($query);
        $query = $this->addElements($query);
        $query = SeoRepository::addSeo($query);

        return $query;
    }

    /**
     * @param string $slug
     * @param string|null $categorySlug
     * @return News|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Exception
     */
    public function getElementBySlugAndCategory(string $slug, string $categorySlug = null): ?News
    {
        $query = self::getQueryForElementBySlug($slug);

        if (!is_null($categorySlug)) {
            $query
                ->andWhere('newsCategory_t.slug =:categorySlug')
                ->setParameter('categorySlug', $categorySlug);
        }

        $tempResults = $query->getQuery()->getOneOrNullResult();

        if (!is_null($tempResults)) {
            $query2 = self::createQuery();
            $query2 = self::helperForShowOnWebsite($query2);
            $query2 = self::helperForElement($query2);
            $query2
                ->andWhere('q.id =:id')
                ->addSelect('newsCategorySeo, newsCategorySeo_t')
                ->leftJoin('newsCategory.seo', 'newsCategorySeo')
                ->leftJoin('newsCategorySeo.translations', 'newsCategorySeo_t')
                ->setParameter('id', $tempResults->getId());

            return $query2->getQuery()->getOneOrNullResult();
        }

        return $tempResults;
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function addNewsAuthor(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addSelect('newsAuthor, newsAuthor_t')
            ->leftJoin('q.newsAuthor', 'newsAuthor', 'WITH', 'newsAuthor.showOnWebsite = :showOnWebsite')
            ->leftJoin('newsAuthor.translations', 'newsAuthor_t');

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function addGalleryImages(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addSelect('galleryImages')
            ->leftJoin('q.galleryImages', 'galleryImages', 'WITH', 'galleryImages.showOnWebsite = :showOnWebsite')
            ->addOrderBy('galleryImages.position', 'ASC');

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function addElements(QueryBuilder $query): QueryBuilder
    {
        $query
            ->addSelect('elements, elements_t')
            ->leftJoin('q.elements', 'elements', 'WITH', 'elements.showOnWebsite = :showOnWebsite')
            ->leftJoin('elements.translations', 'elements_t')
            ->addOrderBy('elements.position', 'ASC');

        return $query;
    }

    /**
     * @param NewsInterface $news
     * @return Query
     * @throws \Exception
     */
    public function getElementsForTagsSliderWithLimit(NewsInterface $news): Query
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);

        if (!is_null($news)) {
            $query
                ->andWhere('q.id !=:id')
                ->setParameter('id', $news->getId());
        }

        $tagsId = [];
        foreach ($news->getTags() as $tag) {
            $tagsId[] = $tag->getId();
        }

        $query
            ->leftJoin('q.tags', 'tags')
            ->andWhere('tags.id IN (:ids)')
            ->setParameter('ids', $tagsId);

        return $query->getQuery();
    }

    /****************************************************************************/

    /**
     * @param string $category
     * @return QueryBuilder
     * @throws \Exception
     */
    private function getQueryBuilderForElementsByCategorySlug(string $category): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
            ->andWhere('newsCategory_t.slug =:categorySlug')
            ->setParameter('categorySlug', $category);

        return $query;
    }

    /**
     * @param string $category
     * @return Query
     * @throws \Exception
     */
    private function getQueryForElementsByCategorySlug(string $category): Query
    {
        return self::getQueryBuilderForElementsByCategorySlug($category)->getQuery();
    }

//    /**
//     * @return array
//     */
//    public function getElementsIndexByTitle(): array
//    {
//        $query = self::createQuery();
//        $temp = $query->getQuery()->getResult();
//        $results = [];
//
//        if (!empty($temp)) {
//            foreach ($temp as $item) {
//                $results[$item->translate()->getTitle()] = $item;
//            }
//        }
//
//        return $results;
//    }
//
//    /**
//     * @param int|null $count
//     * @return Query
//     * @throws \Exception
//     */
//    public function getQueryForLimitElements(int $count = null): Query
//    {
//        $query = self::createQuery();
//        $query = self::helperForShowOnWebsite($query);
//
//        if (!is_null($count)) {
//            $query->setMaxResults($count);
//        }
//
//        return $query->getQuery();
//    }
//

//
//    /**
//     * @param NewsCategoryInterface|null $newsCategory
//     * @return array
//     * @throws \Exception
//     */
//    public function getMonthAndYearInterval(NewsCategoryInterface $newsCategory = null): array
//    {
//        $query = $this->createQueryBuilder('q')
//            ->addOrderBy('q.publishAt', 'DESC')
//            ->andWhere('q.showOnWebsite =:showOnWebsite')
//            ->andWhere('q.publishAt <=:publishAt')
//            ->setParameters([
//                'publishAt' => new \DateTime('now'),
//                'showOnWebsite' => YesOrNoInterface::YES
//            ])
//            ->select('q.publishAt, count(q) as count')
//            ->groupBy('q.publishAt');
//
//        if (!is_null($newsCategory)) {
//            $query
//                ->andWhere('q.newsCategory =:newsCategory')
//                ->setParameter('newsCategory', $newsCategory->getId());
//        }
//
//        $results = $query->getQuery()->getResult();
//
//        $interval = [];
//
//        foreach ($results as $item) {
//            $day = $item['publishAt']->format('m/Y');
//            if (empty($interval[$day])) {
//                $interval[$day]['count'] = 1;
//                $interval[$day]['slug']['month'] = $item['publishAt']->format('m');
//                $interval[$day]['slug']['year'] = $item['publishAt']->format('Y');
//            } else {
//                $interval[$day]['count'] = $interval[$day]['count'] + 1;
//            }
//        }
//
//        return $interval;
//    }
//
    /**
     * @param string $category
     * @return array
     * @throws \Exception
     */
    public function getElementsByCategorySlug(string $category): array
    {
        return self::getQueryForElementsByCategorySlug($category)->getResult();
    }

   /**
    * @param string $category
    * @param int|null $limit
    * @return array
    * @throws \Exception
    */
   public function getElementsByCategoryLimit(string $category, int $limit = null): array
   {
       $query = self::getQueryBuilderForElementsByCategorySlug($category);

       if (!is_null($limit)) {
           $query->setMaxResults($limit);
       }

       return $query->getQuery()->getResult();
   }
//
//    /**
//     * @return array
//     * @throws \Exception
//     */
//    public function getElementsForSiteMap(): array
//    {
//        return self::getQueryForLimitElements(null)->getArrayResult();
//    }
//

//
//    /**
//     * @param int|null $count
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getLimitRANDElements(int $count = null)
//    {
//        $query = self::createQuery();
//        $query = self::helperForShowOnWebsite($query);
//        $query
//            ->addSelect('RAND() as HIDDEN rand')
//            ->addOrderBy('rand');
//
//        if (!is_null($count)) {
//            $query->setMaxResults($count);
//        }
//
//        return $query->getQuery()->getResult();
//    }
//
//
//

//
//    /**
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getElementsByInterval(int $month, int $year, int $day = null): array
//    {
//        return self::getQueryElementsByInterval($month, $year, $day)->getResult();
//    }
//
//    /**
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return Query
//     * @throws \Exception
//     */
//    public function getQueryElementsByInterval(int $month, int $year, int $day = null): Query
//    {
//        $query = self::createQuery();
//        $query = self::helperForShowOnWebsite($query);
//
//        if (!is_null($day)) {
//            $query->andWhere($query->expr()->like('q.publishAt', ':publishAt'))
//                ->setParameter('publishAt', $year . '-' . $month . '-' . $day . '%');
//        } else {
//            $query->andWhere($query->expr()->like('q.publishAt', ':publishAt'))
//                ->setParameter('publishAt', $year . '-' . $month . '%');
//        }
//
//        return $query->getQuery();
//    }
//
//    /**
//     * @param string $categorySlug
//     * @param int $month
//     * @param int $year
//     * @param int|null $day
//     * @return mixed
//     * @throws \Exception
//     */
//    public function getElementsByCategoryAndInterval(string $categorySlug, int $month, int $year, int $day = null): array
//    {
//        return self::getQueryElementsByCategoryAndInterval($categorySlug, $month, $year, $day)->getResult();
//    }
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
//    ): Query
//    {
//        $query = self::createQuery();
//        $query = self::helperForShowOnWebsite($query);
//        $query
//            ->andWhere('newsCategory_t.slug =:categorySlug')
//            ->andWhere($query->expr()->like('q.publishAt1', ':publishAt'))
//            ->setParameter('categorySlug', $categorySlug);
//
//        if (!is_null($day)) {
//            $query
//                ->setParameter('publishAt1', $year . '-' . $month . '-' . $day . '%');
//        } else {
//            $query
//                ->setParameter('publishAt1', $year . '-' . $month . '%');
//        }
//
//        return $query->getQuery();
//    }
//
//    /**
//     * @param string $slug
//     * @param string $categorySlug
//     * @param int $count
//     * @return array
//     * @throws \Exception
//     */
//    public function getSimilarNews(string $slug, string $categorySlug, int $count = 3): array
//    {
//        $query = self::createQuery();
//        $query = self::helperForShowOnWebsite($query);
//        $query
//            ->andWhere('t.slug !=:newsSlug')
//            ->andWhere('newsCategory_t.slug =:categorySlug')
//            ->setParameter('categorySlug', $categorySlug)
//            ->setParameter('newsSlug', $slug)
//            ->setMaxResults($count);
//
//        return $query->getQuery()->getResult();
//    }
//
//    /**
//     * @param string $authorSlug
//     * @return array
//     * @throws \Exception
//     */
//    public function getElementsByAuthor(string $authorSlug): array
//    {
//        $query = self::createQuery();
//        $query = self::addNewsAuthor($query);
//        $query = self::helperForShowOnWebsite($query);
//        $query
//            ->andWhere('newsAuthor_t.slug =:authorSlug')
//            ->setParameter('authorSlug', $authorSlug);
//
//        return $query->getQuery()->getResult();
//    }

    /**
     * @param string $category
     * @return QueryBuilder
     * @throws \Exception
     */
    private function getQueryBuilderForElementsByAuthor(NewsAuthorInterface $author): QueryBuilder
    {
        $query = self::createQuery();
        $query = self::helperForShowOnWebsite($query);
        $query
            ->andWhere('newsAuthor.id =:newsAuthor')
            ->setParameter('newsAuthor', $author->getId());

        return $query;
    }

    /**
     * @param NewsAuthorInterface $author
     * @return Query
     * @throws \Exception
     */
    public function getQueryForElementsByAuthor(NewsAuthorInterface $author): Query
    {
        return self::getQueryBuilderForElementsByAuthor($author)->getQuery();
    }

    /**
     * @param int|null $count
     * @return mixed
     * @throws \Exception
     */
    public function getLimitRANDElements(int $count = null)
    {
        $query = self::createQuery();
        $query
            ->addSelect('RAND() as HIDDEN rand')
            ->andWhere('q.showOnWebsite =:showOnWebsite')
            ->andWhere('q.publishAt <=:publishAt')
            ->andWhere('q.showOnlyOnAuthorPage =:showOnlyOnAuthorPage')
            ->addOrderBy('rand')
            ->addOrderBy('q.publishAt', 'DESC')
            ->addOrderBy('q.position', 'DESC')
            ->groupBy('q')
            ->setParameters([
                'publishAt' => new \DateTime('now'),
                'showOnWebsite' => NewsInterface::YES,
                'showOnlyOnAuthorPage' => NewsInterface::NO,
            ]);

        if (!is_null($count)) {
            $query->setMaxResults($count);
        }

        return $query->getQuery()->getResult();
    }

    public function getElementById(int $id)
    {
        $query = self::createQuery();
        $query
            ->andWhere('q.id =:id')
            ->setMaxResults(1)
            ->setParameter('id', $id);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @return array
     */
    public function getElementsForSiteMap(): array
    {
        return self::getQueryBuilderForLimitElements(null)->getQuery()->getResult();
    }
}
