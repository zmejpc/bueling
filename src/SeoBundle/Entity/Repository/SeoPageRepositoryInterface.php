<?php

namespace SeoBundle\Entity\Repository;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface SeoPageRepositoryInterface
{
    /**
     * @param string $page
     * @return array
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getSeoForPageBySystemName(string $page);
}
