<?php

namespace FrontendBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FaqTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="faq_translation_table")
 * @ORM\Entity
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class FaqTranslation
{
    use ORMBehaviors\Translatable\Translation,
        ORMBehaviors\Timestampable\Timestampable;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="question", type="string", length=255, nullable=false)
     */
    private $question;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="answer", type="text", nullable=false)
     */
    private $answer;

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}