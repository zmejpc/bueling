<?php

namespace ComponentBundle\Entity\IsEnable;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface IsEnableInterface
{
    /**
     * @return bool
     */
    public function getIsEnable(): bool;

    /**
     * @param bool $isEnable
     */
    public function setIsEnable(bool $isEnable): void;
}