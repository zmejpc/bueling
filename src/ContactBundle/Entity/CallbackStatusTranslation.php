<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CallbackStatusTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="callback_status_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class CallbackStatusTranslation implements CallbackStatusTranslationInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
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