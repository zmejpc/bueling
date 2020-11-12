<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Title\TitleTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Title\TitleInterface;
use ComponentBundle\Entity\Description\DescriptionTrait;
use ComponentBundle\Entity\Description\DescriptionInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * NewsTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_element_translation_table", indexes={
 *     @ORM\Index(name="title_idx", columns={"title"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="slug")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsElementTranslation implements TitleInterface, DescriptionInterface
{
    use ORMBehaviors\Translatable\Translation;
    use TitleTrait;
    use DescriptionTrait;
}