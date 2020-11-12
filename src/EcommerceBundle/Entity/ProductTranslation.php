<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * ProductTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="product_translation_table", indexes={
 *     @ORM\Index(name="slug_idx", columns={"slug", "entity_class"}),
 *     @ORM\Index(name="title_idx", columns={"title", "entity_class"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="slug")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class ProductTranslation implements ProductTranslationInterface
{
    use ORMBehaviors\Translatable\Translation,
        ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, nullable=false, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="composition", type="text", nullable=true)
     */
    private $composition;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="sizes", type="text", nullable=true)
     */
    private $sizes;

    /**
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

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
    
    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getComposition(): ?string
    {
        return $this->composition;
    }

    /**
     * @param string $composition
     */
    public function setComposition(?string $composition): void
    {
        $this->composition = $composition;
    }

    /**
     * @return string
     */
    public function getSizes(): ?string
    {
        return $this->sizes;
    }

    /**
     * @param string $sizes
     */
    public function setSizes(?string $sizes): void
    {
        $this->sizes = $sizes;
    }
}
