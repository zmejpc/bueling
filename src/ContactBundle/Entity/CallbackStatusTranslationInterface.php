<?php

namespace ContactBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface CallbackStatusTranslationInterface
{
    public function getTitle(): ?string;

    public function setTitle(string $title): void;
}