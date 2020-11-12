<?php

namespace SeoBundle\Entity;

/**
 * Class SeoTrait
 * @package SeoBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
trait SeoTrait
{
    /**
     * @ORM\OneToOne(targetEntity="SeoBundle\Entity\Seo", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="seo_id", referencedColumnName="id", unique=true)
     */
    private $seo;

    /**
     * @return SeoInterface|null
     */
    public function getSeo(): ?SeoInterface
    {
        return $this->seo;
    }

    /**
     * @param SeoInterface $seo
     */
    public function setSeo(SeoInterface $seo): void
    {
        $this->seo = $seo;
    }
}
