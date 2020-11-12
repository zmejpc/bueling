<?php

namespace NewsBundle\Entity\Repository;

use Doctrine\ORM\QueryBuilder;
use NewsBundle\Entity\NewsAuthor;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsAuthorRepositoryInterface
{
    /**
     * @return QueryBuilder
     */
    public function getNewsAuthorForNewsForm(): QueryBuilder;

    /**
     * @param int $id
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementByIdForDashboardEditOrDeleteFormAction(int $id);

    /**
     * @return array
     */
    public function getElements(): array;

    /**
     * @param string $slug
     * @return NewsAuthor|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getElementBySlug(string $slug): ?NewsAuthor;
}