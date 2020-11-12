<?php

namespace ComponentBundle\Entity\Link;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait LinkTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="link", type="text", nullable=true)
     */
    private $link;

    /**
     * @return null|string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param null|string $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }
}
