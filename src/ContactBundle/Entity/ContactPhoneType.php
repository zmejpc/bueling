<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\Collection;

/**
 * ContactPhoneType
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="contact_phone_type_table")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\ContactPhoneTypeRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneType implements ContactPhoneTypeInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use PositionTrait;
    use YesOrNoTrait;
    use IdTrait;
    use __CallTrait;
    use ShowOnWebsiteTrait;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ContactBundle\Entity\ContactPhone", mappedBy="contactPhoneType", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $contactPhones;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contactPhones = new ArrayCollection();
    }

    /**
     * Get contactPhones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContactPhones(): Collection
    {
        return $this->contactPhones;
    }

    /**
     * @return bool
     */
    public function hasContactPhones(): bool
    {
        return !$this->getContactPhones()->isEmpty();
    }

    /**
     * @param ContactPhoneInterface $contactPhone
     * @return bool
     */
    public function hasContactPhone(ContactPhoneInterface $contactPhone): bool
    {
        return $this->contactPhones->contains($contactPhone);
    }

    /**
     * Remove value
     *
     * @param \ContactBundle\Entity\ContactPhoneInterface $contactPhone
     */
    public function removeContactPhone(ContactPhoneInterface $contactPhone): void
    {
        if ($this->hasContactPhone($contactPhone)) {
            $this->contactPhones->removeElement($contactPhone);
        }
    }

    /**
     * @param ContactPhoneInterface $contactPhone
     */
    public function addContactPhone(ContactPhoneInterface $contactPhone): void
    {
        if (!$this->hasContactPhone($contactPhone)) {
            $contactPhone->setContactPhoneType($this);
            $this->contactPhones->add($contactPhone);
        }
    }
}
