<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\Message\MessageInterface;
use ComponentBundle\Entity\Id\IdInterface;
use UserBundle\Entity\CreatedBy\CreatedByInterface;
use UserBundle\Entity\UpdatedBy\UpdatedByInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactInterface extends
    CreatedByInterface,
    UpdatedByInterface,
    IdInterface,
    MessageInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * @return null|string
     */
    public function getSubject(): ?string;

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void;

    /**
     * @return ContactStatusInterface|null
     */
    public function getStatus(): ?ContactStatusInterface;

    /**
     * @param ContactStatusInterface $status
     */
    public function setStatus(ContactStatusInterface $status): void;
}
