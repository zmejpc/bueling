<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Title\TitleTrait;

/**
 * NewsQuizTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_quiz_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizTranslation implements NewsQuizTranslationInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;
    use TitleTrait;
}