<?php

namespace ComponentBundle\Entity\Img;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ImgInterface
{
    /**
     * @return string
     */
    public function getImg(): ?string;

    /**
     * @param string $img
     */
    public function setImg(?string $img): void;
}