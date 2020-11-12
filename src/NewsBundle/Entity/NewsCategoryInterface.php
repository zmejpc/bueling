<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTraitInterface;
use ComponentBundle\Entity\Id\IdInterface;
use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsCategoryInterface extends IdInterface, PositionInterface, ShowOnWebsiteInterface,
    SeoTraitInterface, NewsCollectionAwareInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return bool|null
     */
    public function getShowInMenu(): ?bool;

    /**
     * @param bool $showInMenu
     * @return NewsCategoryInterface
     */
    public function setShowInMenu(bool $showInMenu): NewsCategoryInterface;

    /**
     * @param NewsInterface $news
     */
    public function addNews(NewsInterface $news): void;

    /**
     * @param NewsInterface $news
     * @return bool
     */
    public function hasNews(NewsInterface $news): bool;

    /**
     * @param NewsInterface $news
     */
    public function removeNews(NewsInterface $news): void;

    /**
     * @return Collection
     */
    public function getNews(): Collection;
}