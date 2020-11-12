<?php

namespace SeoBundle\Entity;

/**
 * Interface SeoTraitInterface
 * @package SeoBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface SeoTraitInterface
{
    /**
     * @return SeoInterface|null
     */
    public function getSeo(): ?SeoInterface;

    /**
     * @param SeoInterface $seo
     */
    public function setSeo(SeoInterface $seo): void;
}
