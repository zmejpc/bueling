<?php

namespace ComponentBundle\Entity\PosterAlt;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface PosterAltInterface
{
    /**
     * @return string
     */
    public function getPosterAlt(): ?string;

    /**
     * @param string $posterAlt
     */
    public function setPosterAlt(?string $posterAlt): void;
}