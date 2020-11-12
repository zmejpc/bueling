<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\Message\MessageTrait;
use ComponentBundle\Entity\Id\IdTrait;
use Doctrine\ORM\Mapping as ORM;
use Ecommerce\Entity\Product;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use UserBundle\Entity\CreatedBy\CreatedByTrait;
use UserBundle\Entity\UpdatedBy\UpdatedByTrait;
use UserBundle\Entity\User;

/**
 * Contact
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_table")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\ContactRepository")
 * @author Design studio origami <https://origami.ua>
 */
class Contact implements ContactInterface
{
    use ORMBehaviors\Timestampable\Timestampable;
    use CreatedByTrait, UpdatedByTrait;
    use IdTrait;
    use MessageTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var \ContactBundle\Entity\ContactStatus
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="ContactBundle\Entity\ContactStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="contact_status_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $status;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="surname", type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="phone", type="string", length=255, nullable=false)
     */
    private $phone;

    /**
     * @var \Ecommerce\Entity\Product
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Ecommerce\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $product;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
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

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getSubject();
    }

    /**
     * @return string
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return ContactStatusInterface|null
     */
    public function getStatus(): ?ContactStatusInterface
    {
        return $this->status;
    }

    /**
     * @param ContactStatusInterface $status
     */
    public function setStatus(ContactStatusInterface $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     */
    public function setSurname(string $surname): void
    {
        $this->surname = $surname;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
