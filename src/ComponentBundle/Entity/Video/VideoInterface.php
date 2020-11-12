<?php

namespace ComponentBundle\Entity\Video;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface VideoInterface
{
    /**
     * @return string|null
     */
    public function getVideo(): ?string;

    /**
     * @param string|null $video
     */
    public function setVideo(?string $video): void;
}