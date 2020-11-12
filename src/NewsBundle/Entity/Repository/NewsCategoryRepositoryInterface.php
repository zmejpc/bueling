<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsCategory;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsCategoryRepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function getNewsCategoryForNewsForm(): QueryBuilder;

    /**
     * @return mixed
     */
    public function getElementsForMenu(): array;

    /**
     * @return mixed
     */
    public function getAsideElementsOnMain(int $count = null): array;

    /**
     * @return array
     */
    public function getElementsForSiteMap(): array;

    /**
     * @return array
     */
    public function getElements(): array;

    /**
     * @return array
     */
    public function getElementsIndexByTitle(): array;

    /**
     * @param string $slug
     * @return NewsCategory|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementBySlug(string $slug): ?NewsCategory;
}