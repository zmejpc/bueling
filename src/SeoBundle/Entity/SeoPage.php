<?php

namespace SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\SystemName\SystemNameTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * SeoPage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="seo_page_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"}),
 *     @ORM\UniqueConstraint(name="seo_UNIQUE", columns={"seo_id"})
 * }, indexes={@ORM\Index(name="system_name_idx", columns={"system_name"})
 * })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="SeoBundle\Entity\Repository\SeoPageRepository")
 * @author Design studio origami <https://origami.ua>
 */
class SeoPage implements SeoPageInterface, SeoTraitInterface
{
    use ORMBehaviors\Timestampable\Timestampable;
    use IdTrait;
    use SeoTrait;
    use SystemNameTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->getSystemName();
    }

    /**
     * @return mixed|object
     */
    public function getSeoForPage()
    {
        return $this->getSeo()->getSeoForPage();
    }
}
