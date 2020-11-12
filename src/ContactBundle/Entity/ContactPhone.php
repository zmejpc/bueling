<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactPhone
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_phone_table")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\ContactPhoneRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhone implements ContactPhoneInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    use PositionTrait;
    use YesOrNoTrait;
    use IdTrait;
    use ShowOnWebsiteTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var \ContactBundle\Entity\ContactPhoneType
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="ContactBundle\Entity\ContactPhoneType", inversedBy="contactPhones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_phone_type_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $contactPhoneType;

    /**
     * @return ContactPhoneTypeInterface|null
     */
    public function getContactPhoneType(): ?ContactPhoneTypeInterface
    {
        return $this->contactPhoneType;
    }

    /**
     * @param ContactPhoneTypeInterface $contactPhoneType
     */
    public function setContactPhoneType(ContactPhoneTypeInterface $contactPhoneType): void
    {
        $this->contactPhoneType = $contactPhoneType;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
}
