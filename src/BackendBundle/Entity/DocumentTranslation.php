<?php

namespace BackendBundle\Entity;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use ComponentBundle\Entity\Title\TitleTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentTranslation
 *
 * @ORM\Table(name="document_translation_table")
 * @ORM\Entity
 */
class DocumentTranslation
{
    use Translation;
    use TitleTrait;
}
