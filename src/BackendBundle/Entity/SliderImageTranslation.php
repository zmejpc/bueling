<?php

namespace BackendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SliderImageTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="slider_image_translation_table")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class SliderImageTranslation
{
    use ORMBehaviors\Translatable\Translation,
        ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
      * @var string
    *
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
}