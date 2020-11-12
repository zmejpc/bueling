<?php

namespace ComponentBundle\Entity\SystemName;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait SystemNameTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="system_name", type="string", length=255, nullable=false, unique=true)
     */
    private $systemName;

    /**
     * @return string
     */
    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    /**
     * @param string $systemName
     */
    public function setSystemName(string $systemName): void
    {
        $this->systemName = $systemName;
    }
}
