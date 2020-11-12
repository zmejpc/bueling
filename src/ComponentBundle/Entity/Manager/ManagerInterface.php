<?php

namespace ComponentBundle\Entity\Manager;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ManagerInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return null|string
     */
    public function getEmail(): ?string;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void;

    /**
     * @return null|string
     */
    public function getName(): ?string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return bool
     */
    public function getIsSendForEmail(): bool;

    /**
     * @param bool $isSendForEmail
     */
    public function setIsSendForEmail(bool $isSendForEmail): void;
}
