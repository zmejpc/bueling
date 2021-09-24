<?php

namespace Ecommerce\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * TechnicTypeTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="technic_type_translation_table", indexes={
 *     @ORM\Index(name="title_idx", columns={"title", "entity_class"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="title")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class TechnicTypeTranslation
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
}
