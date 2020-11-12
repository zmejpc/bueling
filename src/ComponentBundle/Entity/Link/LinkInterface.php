<?php

namespace ComponentBundle\Entity\Link;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface LinkInterface
{
    /**
     * @return null|string
     */
    public function getLink(): ?string;

    /**
     * @param null|string $link
     */
    public function setLink(?string $link): void;
}
