<?php

namespace Ecommerce\Entity;

use Ecommerce\Entity\ProductAssociated;
use Ecommerce\Entity\ProductDiscount;

/**
 * Interface ProductInterface
 * @package Ecommerce\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface ProductInterface
{
    /* YES / NO */
    public const YES = 1;
    public const NO = 0;
    
    public static function yesOrNo();

    public static function yesOrNoForm();

    public function __call($method, $arguments);

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return int
     */
    public function getPosition(): ?int;

    /**
     * @param int $position
     */
    public function setPosition(int $position): void;

    /**
     * @param ProductCategory $category
     * @return mixed
     */
    public function hasProductCategory(ProductCategory $category);

    /**
     * @param ProductCategory $category
     * @return mixed
     */
    public function addCategory(ProductCategory $category);

    /**
     * @param ProductCategory $category
     * @return mixed
     */
    public function removeCategory(ProductCategory $category);

    /**
     * @return mixed
     */
    public function getCategories();

    /**
     * @return mixed
     */
    public function hasCategories();
    
    /**
     * @param ProductGalleryImage $galleryImage
     * @return mixed
     */
    public function hasGalleryImage(ProductGalleryImage $galleryImage);

    /**
     * @param ProductGalleryImage $galleryImage
     * @return mixed
     */
    public function addGalleryImage(ProductGalleryImage $galleryImage);

    /**
     * @param ProductGalleryImage $galleryImage
     * @return mixed
     */
    public function removeGalleryImage(ProductGalleryImage $galleryImage);

    /**
     * @return mixed
     */
    public function getGalleryImages();

    /**
     * @return mixed
     */
    public function hasGalleryImages();

    public function getTopSale(): bool;

    public function setTopSale(bool $topSale): void;

    public function getIsNova(): bool;

    public function setIsNova(bool $isNova): void;
}