<?php

namespace SeoBundle\Entity;

/**
 * Interface SeoTranslationInterface
 * @package SeoBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface SeoTranslationInterface
{
    /**
     * @return null|string
     */
    public function getMetaTitle(): ?string;

    /**
     * @param string|null $metaTitle
     */
    public function setMetaTitle(?string $metaTitle): void;

    /**
     * @return null|string
     */
    public function getH1(): ?string;

    /**
     * @param string $h1
     */
    public function setH1(?string $h1): void;

    /**
     * @return null|string
     */
    public function getBreadcrumb(): ?string;

    /**
     * @param string $breadcrumb
     */
    public function setBreadcrumb(?string $breadcrumb): void;

    /**
     * @return null|string
     */
    public function getMetaKeyword(): ?string;

    /**
     * @param null|string $metaKeyword
     */
    public function setMetaKeyword(?string $metaKeyword): void;

    /**
     * @return null|string
     */
    public function getMetaDescription(): ?string;

    /**
     * @param null|string $metaDescription
     */
    public function setMetaDescription(?string $metaDescription): void;
}
