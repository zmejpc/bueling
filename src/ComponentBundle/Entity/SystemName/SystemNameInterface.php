<?php

namespace ComponentBundle\Entity\SystemName;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface SystemNameInterface
{
    /**
     * @return null|string
     */
    public function getSystemName(): ?string;

    /**
     * @param string $systemName
     */
    public function setSystemName(string $systemName): void;
}
