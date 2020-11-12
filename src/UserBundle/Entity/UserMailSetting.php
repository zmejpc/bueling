<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Id\IdInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\SystemName\SystemNameTrait;
use ComponentBundle\Entity\MailSetting\MailSettingTrait;
use ComponentBundle\Entity\MailSetting\MailSettingInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * UserMailSetting
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="user_mail_setting_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"})
 * }, indexes={
 *     @ORM\Index(name="system_name_idx", columns={"system_name"}),
 * })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="UserBundle\Entity\Repository\UserMailSettingRepository")
 * @author Design studio origami <https://origami.ua>
 */
class UserMailSetting implements MailSettingInterface, IdInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use IdTrait;
    use SystemNameTrait;
    use MailSettingTrait;
}