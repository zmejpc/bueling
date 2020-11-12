<?php

namespace UserBundle\Entity\PersonalInformation;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait PersonalInformationExtendedTrait
{
    use PersonalInformationTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

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
}
