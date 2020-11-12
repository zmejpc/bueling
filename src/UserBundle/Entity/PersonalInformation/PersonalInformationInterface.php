<?php

namespace UserBundle\Entity\PersonalInformation;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface PersonalInformationInterface
{
    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string|null
     */
    public function getPhoneNumber(): ?string;

    /**
     * @param string $phone
     */
    public function setPhoneNumber(string $phone): void;
}
