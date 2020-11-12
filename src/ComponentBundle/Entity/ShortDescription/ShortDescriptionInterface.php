<?php

namespace ComponentBundle\Entity\ShortDescription;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ShortDescriptionInterface
{
    /**
     * @return string
     */
    public function getShortDescription(): ?string;

    /**
     * @param string $shortDescription
     */
    public function setShortDescription(?string $shortDescription): void;
}
