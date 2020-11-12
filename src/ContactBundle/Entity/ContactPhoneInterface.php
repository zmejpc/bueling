<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactPhoneInterface extends YesOrNoInterface, IdInterface, PositionInterface, ShowOnWebsiteInterface
{
    /**
     * @return null|string
     */
    public function getPhone(): ?string;

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void;

    /**
     * @return ContactPhoneTypeInterface|null
     */
    public function getContactPhoneType(): ?ContactPhoneTypeInterface;

    /**
     * @param ContactPhoneTypeInterface $contactPhoneType
     */
    public function setContactPhoneType(ContactPhoneTypeInterface $contactPhoneType): void;
}
