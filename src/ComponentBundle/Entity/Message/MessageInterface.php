<?php

namespace ComponentBundle\Entity\Message;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface MessageInterface
{
    /**
     * @return null|string
     */
    public function getMessage(): ?string;

    /**
     * @param string $message
     */
    public function setMessage(string $message): void;
}
