<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\Title\TitleInterface;
use ComponentBundle\Entity\Title\TitleTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ContactPhoneTypeTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_phone_type_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class ContactPhoneTypeTranslation implements TitleInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;

    use TitleTrait;
}
