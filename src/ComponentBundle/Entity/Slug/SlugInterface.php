<?php

namespace ComponentBundle\Entity\Slug;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface SlugInterface
{
    /**
     * @return string
     */
    public function getSlug(): ?string;

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void;
}
