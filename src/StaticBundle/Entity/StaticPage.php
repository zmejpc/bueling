<?php

namespace StaticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use ComponentBundle\Entity\Id\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use SeoBundle\Entity\SeoTraitInterface;
use ComponentBundle\Entity\Poster\PosterTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\SystemName\SystemNameTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * StaticPage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="static_page_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="system_name_UNIQUE", columns={"system_name"}),
 *     @ORM\UniqueConstraint(name="seo_UNIQUE", columns={"seo_id"})
 * }, indexes={
 *     @ORM\Index(name="seo_idx", columns={"seo_id"}),
 *     @ORM\Index(name="system_name_idx", columns={"system_name"})
 *     })
 * @UniqueEntity\UniqueEntity(fields="systemName")
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\StaticPageRepository")
 * @author Design studio origami <https://origami.ua>
 */
class StaticPage implements StaticPageInterface, SeoTraitInterface
{
    use ORMBehaviors\Translatable\Translatable,
        ORMBehaviors\Timestampable\Timestampable;
    use SeoTrait;
    use SystemNameTrait;
    use IdTrait;
    use PosterTrait;

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }

    /**
     * @ORM\OneToMany(targetEntity="StaticPageGalleryImage", mappedBy="staticPage", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $galleryImages;

    public function __construct()
    {
        $this->galleryImages = new ArrayCollection();
    }

    /**
     * @return Collection|StaticPageGalleryImage[]
     */
    public function getGalleryImages(): Collection
    {
        return $this->galleryImages;
    }

    public function addGalleryImage(StaticPageGalleryImage $galleryImage): self
    {
        if (!$this->galleryImages->contains($galleryImage)) {
            $this->galleryImages[] = $galleryImage;
            $galleryImage->setStaticPage($this);
        }

        return $this;
    }

    public function removeGalleryImage(StaticPageGalleryImage $galleryImage): self
    {
        if ($this->galleryImages->contains($galleryImage)) {
            $this->galleryImages->removeElement($galleryImage);
            // set the owning side to null (unless already changed)
            if ($galleryImage->getStaticPage() === $this) {
                $galleryImage->setStaticPage(null);
            }
        }

        return $this;
    }
}
