<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTraitInterface;
use ComponentBundle\Entity\Id\IdInterface;
use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Video\VideoInterface;
use ComponentBundle\Entity\Poster\PosterInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsInterface extends IdInterface, PositionInterface, ShowOnWebsiteInterface,
    SeoTraitInterface, PosterInterface, VideoInterface, YesOrNoInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return \DateTimeInterface|null
     * @throws \Exception
     */
    public function getPublishAt(): ?\DateTimeInterface;

    /**
     * @param \DateTimeInterface|null $publishAt
     * @return NewsInterface
     * @throws \Exception
     */
    public function setPublishAt(?\DateTimeInterface $publishAt): NewsInterface;

    /**
     * @return NewsCategory|null
     */
    public function getNewsCategory(): ?NewsCategory;

     /**
     * @return bool
     */
    public function getShowOnlyOnAuthorPage(): bool;

    /**
     * @param bool $showOnlyOnAuthorPage
     */
    public function setShowOnlyOnAuthorPage(bool $showOnlyOnAuthorPage): void;

    /**
     * @param NewsCategoryInterface|null $newsCategory
     * @return NewsInterface
     */
    public function setNewsCategory(?NewsCategoryInterface $newsCategory): NewsInterface;

    /**
     * @param NewsGalleryImage $galleryImage
     * @return bool
     */
    public function hasGalleryImage(NewsGalleryImage $galleryImage): bool;

    /**
     * @param NewsGalleryImage $galleryImage
     */
    public function addGalleryImage(NewsGalleryImage $galleryImage): void;

    /**
     * @param NewsGalleryImage $galleryImage
     */
    public function removeGalleryImage(NewsGalleryImage $galleryImage): void;

    /**
     * @return Collection
     */
    public function getGalleryImages(): Collection;

    /**
     * @return bool
     */
    public function hasGalleryImages(): bool;

    /**
     * @return Collection|NewsElement[]
     */
    public function getElements(): Collection;

    /**
     * @param NewsElement $element
     * @return NewsInterface
     */
    public function addElement(NewsElement $element): NewsInterface;

    /**
     * @param NewsElement $element
     * @return NewsInterface
     */
    public function removeElement(NewsElement $element): NewsInterface;

    /**
     * @return NewsAuthor|null
     */
    public function getNewsAuthor(): ?NewsAuthor;

    /**
     * @param NewsAuthor|null $newsAuthor
     */
    public function setNewsAuthor(?NewsAuthor $newsAuthor): void;

    /**
     * @return bool
     */
    public function isMain(): ?bool;

    /**
     * @param bool $isMain
     */
    public function setIsMain(bool $isMain): void;
}