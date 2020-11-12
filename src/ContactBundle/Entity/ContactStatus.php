<?php

namespace ContactBundle\Entity;

use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\SystemName\SystemNameTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * ContactStatus
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="contact_status_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"})
 * }, indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="system_name_idx", columns={"system_name"})
 * })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="ContactBundle\Entity\Repository\ContactStatusRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ContactStatus implements ContactStatusInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use PositionTrait;
    use SystemNameTrait;
    use YesOrNoTrait;
    use IdTrait;
    use __CallTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }
}
