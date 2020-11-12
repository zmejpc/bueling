<?php

namespace ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\Manager\ManagerTrait;
use ComponentBundle\Entity\Manager\ManagerInterface;

/**
 * ContactManager
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_manager_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"})
 * }, indexes={
 *     @ORM\Index(name="email_idx", columns={"email"}),
 *     @ORM\Index(name="is_send_for_email_idx", columns={"is_send_for_email"}),
 * })
 * @UniqueEntity\UniqueEntity(fields="email")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\ContactManagerRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ContactManager implements ManagerInterface, IdInterface, YesOrNoInterface
{
    use ORMBehaviors\Timestampable\Timestampable;

    use ManagerTrait;
    use YesOrNoTrait;
    use IdTrait;
}
