<?php

namespace SeoBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;

/**
 * Interface SeoInterface
 * @package SeoBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface SeoInterface extends IdInterface
{
    /**
     * @return mixed
     */
    public function getSeoForPage();
}
