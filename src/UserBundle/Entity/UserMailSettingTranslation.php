<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\MailSetting\MailSettingTranslationExtendedTrait;
use ComponentBundle\Entity\MailSetting\MailSettingTranslationExtendedInterface;

/**
 * UserMailSettingTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="user_mail_setting_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class UserMailSettingTranslation implements MailSettingTranslationExtendedInterface
{
    use ORMBehaviors\Translatable\Translation;
    use MailSettingTranslationExtendedTrait;
}