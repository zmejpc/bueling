<?php

namespace Ecommerce\Entity;

/**
 * Interface ProductTranslationInterface
 * @package Ecommerce\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface ProductTranslationInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;

    public function getSlug(): ?string;

    public function setSlug(string $slug): void;

    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}