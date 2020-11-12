<?php

namespace Ecommerce\Entity;

/**
 * Interface ProductCategoryTranslationInterface
 * @package Ecommerce\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface ProductCategoryTranslationInterface
{
    /**
     * @return string
     */
    public function getTitle(): ?string;

    /**
     * @param string $title
     */
    public function setTitle(string $title): void;

    /**
     * @return string
     */
    public function getSlug(): ?string;

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void;

    /**
     * @return string
     */
    public function getDescription(): ?string;

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void;

    /**
     * @return string
     */
    public function getTreeTitle(): ?string;

    /**
     * @param string $treeTitle
     */
    public function setTreeTitle(string $treeTitle): void;
}