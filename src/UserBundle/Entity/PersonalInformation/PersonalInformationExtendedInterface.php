<?php

namespace UserBundle\Entity\PersonalInformation;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface PersonalInformationExtendedInterface extends PersonalInformationInterface
{
    /**
     * @return string|null
     */
    public function getEmail(): ?string;

    /**
     * @param string|null $email
     */
    public function setEmail(?string $email): void;
}
