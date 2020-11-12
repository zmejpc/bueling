<?php

namespace ComponentBundle\Entity\Slug;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait SlugUniqueFalseTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Slug(fields={"title"}, unique=false)
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=false)
     */
    private $slug;

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }
}
