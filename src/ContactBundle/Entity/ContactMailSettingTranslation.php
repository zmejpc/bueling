<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\MailSetting\MailSettingTranslationExtendedInterface;
use ComponentBundle\Entity\MailSetting\MailSettingTranslationExtendedTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * ContactMailSettingTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_mail_setting_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class ContactMailSettingTranslation implements MailSettingTranslationExtendedInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translation;

    use MailSettingTranslationExtendedTrait;
}
