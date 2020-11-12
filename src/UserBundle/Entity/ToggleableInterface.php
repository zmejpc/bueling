<?php

namespace UserBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ToggleableInterface
{
    /**
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * @param bool $enabled
     */
    public function setEnabled(?bool $enabled): void;

    /**
     *
     */
    public function enable(): void;

    /**
     *
     */
    public function disable(): void;
}
