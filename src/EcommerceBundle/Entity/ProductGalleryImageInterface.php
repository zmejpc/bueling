<?php

namespace Ecommerce\Entity;

/**
 * Interface ProductGalleryImageInterface
 * @package Ecommerce\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface ProductGalleryImageInterface
{
    /* YES / NO */
    public const YES = 1;
    public const NO = 0;

    public static function yesOrNo();

    public static function yesOrNoForm();

    /**
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * @return string
     */
    public function getImage(): ?string;

    /**
     * @param string $image
     */
    public function setImage(?string $image): void;

    /**
     * @return Product
     */
    public function getProduct(): Product;

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void;
}