<?php

namespace SeoBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\SystemName\SystemNameInterface;

/**
 * Interface SeoPageInterface
 * @package SeoBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface SeoPageInterface extends IdInterface, SystemNameInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return mixed
     */
    public function getSeoForPage();
}
