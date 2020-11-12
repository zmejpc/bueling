<?php

namespace ComponentBundle\Entity\ShowOnWebsite;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ShowOnWebsiteInterface
{
    /**
     * @return bool|null
     */
    public function getShowOnWebsite(): ?bool;

    /**
     * @param bool $showOnWebsite
     */
    public function setShowOnWebsite(bool $showOnWebsite): void;
}
