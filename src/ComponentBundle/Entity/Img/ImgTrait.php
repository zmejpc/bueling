<?php

namespace ComponentBundle\Entity\Img;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait ImgTrait
{

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="img", type="text", nullable=true)
     */
    private $img;

    /**
     * @return string
     */
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * @param string $img
     */
    public function setImg(?string $img): void
    {
        $this->img = $img;
    }
}