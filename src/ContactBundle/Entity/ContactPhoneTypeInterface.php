<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\__Call\__CallInterface;
use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface ContactPhoneTypeInterface extends
    YesOrNoInterface,
    IdInterface,
    __CallInterface,
    PositionInterface,
    ShowOnWebsiteInterface
{
    /**
     * ContactPhoneTypeInterface constructor.
     */
    public function __construct();

    /**
     * @return Collection
     */
    public function getContactPhones(): Collection;

    /**
     * @return bool
     */
    public function hasContactPhones(): bool;

    /**
     * @param ContactPhoneInterface $contactPhone
     * @return bool
     */
    public function hasContactPhone(ContactPhoneInterface $contactPhone): bool;

    /**
     * @param ContactPhoneInterface $contactPhone
     */
    public function removeContactPhone(ContactPhoneInterface $contactPhone): void;

    /**
     * @param ContactPhoneInterface $contactPhone
     */
    public function addContactPhone(ContactPhoneInterface $contactPhone): void;
}
