<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Title\TitleTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Title\TitleInterface;

/**
 * ContactStatusTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_status_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class ContactStatusTranslation implements TitleInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;

    use TitleTrait;
}
