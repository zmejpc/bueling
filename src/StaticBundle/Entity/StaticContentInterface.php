<?php

namespace StaticBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Img\ImgInterface;

/**
 * Interface StaticContentInterface
 * @package StaticBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface StaticContentInterface extends IdInterface, ImgInterface
{
    /**
     * @return null|string
     */
    public function getLinkName(): ?string;

    /**
     * @param string $linkName
     */
    public function setLinkName(string $linkName): void;

    /**
     * @return null|string
     */
    public function getPage(): ?string;

    /**
     * @param string $page
     */
    public function setPage(string $page): void;
}
