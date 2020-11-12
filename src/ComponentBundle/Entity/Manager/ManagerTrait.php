<?php

namespace ComponentBundle\Entity\Manager;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait ManagerTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=false, unique=true)
     */
    private $email;

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="is_send_for_email", type="boolean", nullable=false)
     */
    private $isSendForEmail = YesOrNoInterface::YES;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getName() . ' - ' . $this->getEmail();
    }

    /**
     * @return null|string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

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
     * @return bool
     */
    public function getIsSendForEmail(): bool
    {
        return $this->isSendForEmail;
    }

    /**
     * @param bool $isSendForEmail
     */
    public function setIsSendForEmail(bool $isSendForEmail): void
    {
        $this->isSendForEmail = $isSendForEmail;
    }
}
