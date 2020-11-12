<?php

namespace Ecommerce\Entity;

/**
 * Interface ProductCategoryInterface
 * @package Ecommerce\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface ProductCategoryInterface
{
    /* YES / NO */
    public const YES = 1;
    public const NO = 0;

    public function __call($method, $arguments);

    public function __toString();

    public function __construct();

    /**
     * @return int|null
     */
    public function getId(): ?int;

    public static function yesOrNo();

    public static function yesOrNoForm();

    public function categoryTree();

    public function getParents(ProductCategory $category, &$arr);

    public function getAncestors();

    public function setParent(ProductCategory $parent = null);

    public function getParent();

    public function addChild(ProductCategory $child);

    public function hasChild(ProductCategory $productCategory);

    public function hasChildren();

    public function removeChild(ProductCategory $child);

    public function getChildren();

    public function getPoster(): ?string;

    public function setPoster(string $poster): void;

    /**
     * @return string
     */
    public function getSlug(): ?string;

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void;
}