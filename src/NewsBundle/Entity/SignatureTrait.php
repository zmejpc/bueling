<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait SignatureTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="signature", type="string", length=255, nullable=true)
     */
    private $signature;

    /**
     * @return string
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @param string $signature
     */
    public function setSignature(?string $signature): void
    {
        $this->signature = $signature;
    }
}