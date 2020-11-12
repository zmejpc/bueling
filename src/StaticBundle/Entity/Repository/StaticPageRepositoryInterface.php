<?php

namespace StaticBundle\Entity\Repository;

use StaticBundle\Entity\StaticPageInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface StaticPageRepositoryInterface
{
    /**
     * @param string $systemName
     * @return StaticPageInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getStaticPageBySystemName(string $systemName): ?StaticPageInterface;
}
