<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Title\TitleTrait;
use ComponentBundle\Entity\Slug\SlugInterface;
use ComponentBundle\Entity\Title\TitleInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Slug\SlugUniqueTrueTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * NewsTagTranslation
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_tag_translation_table", indexes={
 *     @ORM\Index(name="slug_idx", columns={"slug"}),
 *     @ORM\Index(name="title_idx", columns={"title"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields="slug")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsTagTranslation implements TitleInterface, SlugInterface
{
    use ORMBehaviors\Translatable\Translation;
    use TitleTrait;
    use SlugUniqueTrueTrait;
}
