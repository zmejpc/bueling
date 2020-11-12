<?php

namespace ComponentBundle\Entity\Poster;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface PosterInterface
{
    /**
     * @return string
     */
    public function getPoster(): ?string;

    /**
     * @param string $poster
     */
    public function setPoster(?string $poster): void;
}