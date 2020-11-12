<?php

namespace ComponentBundle\Entity\RemoteUrl;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface RemoteUrlInterface
{
    /**
     * @return null|string
     */
    public function getRemoteUrl(): ?string;

    /**
     * @param null|string $remoteUrl
     */
    public function setRemoteUrl(?string $remoteUrl): void;
}
