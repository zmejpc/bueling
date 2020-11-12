<?php

namespace ComponentBundle\Entity\Slug;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait SlugUniqueTrueTrait
{
    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Slug(fields={"title"}, unique=true)
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
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
