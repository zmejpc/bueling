<?php

namespace SeoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * SeoTranslation
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="seo_translation_table")
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class SeoTranslation implements SeoTranslationInterface
{
    use ORMBehaviors\Translatable\Translation;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="text", nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="h1", type="string", length=255, nullable=true)
     */
    private $h1;

    /**
     * @var string
     *
     * @ORM\Column(name="breadcrumb", type="string", length=255, nullable=true)
     */
    private $breadcrumb;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keyword", type="string", length=255, nullable=true)
     */
    private $metaKeyword;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @return string
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * @param string $metaTitle
     */
    public function setMetaTitle(?string $metaTitle): void
    {
        $this->metaTitle = $metaTitle;
    }

    /**
     * @return string
     */
    public function getH1(): ?string
    {
        return $this->h1;
    }

    /**
     * @param $h1
     */
    public function setH1(?string $h1): void
    {
        $this->h1 = $h1;
    }

    /**
     * @return string
     */
    public function getBreadcrumb(): ?string
    {
        return $this->breadcrumb;
    }

    /**
     * @param $breadcrumb
     */
    public function setBreadcrumb(?string $breadcrumb): void
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * @return string
     */
    public function getMetaKeyword(): ?string
    {
        return $this->metaKeyword;
    }

    /**
     * @param string $metaKeyword
     */
    public function setMetaKeyword(?string $metaKeyword): void
    {
        $this->metaKeyword = $metaKeyword;
    }

    /**
     * @return string
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    /**
     * @param string $metaDescription
     */
    public function setMetaDescription(?string $metaDescription): void
    {
        $this->metaDescription = $metaDescription;
    }
}
