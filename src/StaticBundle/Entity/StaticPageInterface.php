<?php

namespace StaticBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\SystemName\SystemNameInterface;

/**
 * Interface StaticPageInterface
 * @package StaticBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface StaticPageInterface extends IdInterface, SystemNameInterface
{
    /**
     * @return string
     */
    public function __toString(): string;
}
