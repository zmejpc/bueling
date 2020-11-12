<?php

namespace ComponentBundle\Entity\Position;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface PositionInterface
{
    /**
     * @return int|null
     */
    public function getPosition(): ?int;

    /**
     * @param int $position
     */
    public function setPosition(int $position): void;
}
