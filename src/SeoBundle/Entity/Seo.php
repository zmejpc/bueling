<?php

namespace SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Seo
 *
 * @ORM\Table(name="seo_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class Seo implements SeoInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $id;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return object
     */
    public function getSeoForPage()
    {
        $translate = $this->translate();

        return (object)[
            'h1' => $translate->getH1(),
            'title' => $translate->getMetaTitle(),
            'keyword' => $translate->getMetaKeyword(),
            'breadcrumb' => $translate->getBreadcrumb(),
            'description' => $translate->getMetaDescription(),
        ];
    }
}
