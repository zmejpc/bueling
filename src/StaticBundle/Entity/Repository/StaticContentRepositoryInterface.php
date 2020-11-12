<?php

namespace StaticBundle\Entity\Repository;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface StaticContentRepositoryInterface
{
    /**
     * @param string $page
     * @return array
     * @throws \Doctrine\ORM\Query\QueryException
     */
    public function getByPage(string $page): array;
}
