<?php

namespace StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Title\TitleTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Title\TitleInterface;
use ComponentBundle\Entity\Description\DescriptionTrait;
use ComponentBundle\Entity\ShortDescription\ShortDescriptionTrait;
use ComponentBundle\Entity\Description\DescriptionInterface;

/**
 * StaticPageTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="static_page_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class StaticPageTranslation implements TitleInterface, DescriptionInterface
{
    use ORMBehaviors\Translatable\Translation;
    use TitleTrait;
    use DescriptionTrait;
    use ShortDescriptionTrait;
}
