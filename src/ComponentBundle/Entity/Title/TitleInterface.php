<?php

namespace ComponentBundle\Entity\Title;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface TitleInterface
{
    /**
     * @return null|string
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     */
    public function setTitle(string $title): void;
}
