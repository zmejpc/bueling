<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Title\TitleTrait;

/**
 * NewsQuizOptionTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_quiz_option_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizOptionTranslation implements NewsQuizOptionTranslationInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;
    use TitleTrait;
}