<?php

namespace ComponentBundle\Entity\Description;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface DescriptionInterface
{
    /**
     * @return string|null
     */
    public function getDescription(): ?string;

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void;
}
