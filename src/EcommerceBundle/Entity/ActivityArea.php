<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\Collection;
use SeoBundle\Entity\SeoTrait;
use FrontendBundle\Entity\Faq;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\Poster\PosterTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * ActivityArea
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="activity_area_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="seo_UNIQUE", columns={"seo_id"})
 * }, indexes={
 *     @ORM\Index(name="seo_idx", columns={"seo_id"}),
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ActivityAreaRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ActivityArea
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use SeoTrait;
    use PositionTrait;
    use PosterTrait;
    use ShowOnWebsiteTrait;

    public const YES = 1;
    public const NO = 0;

    /**
     * @param $method
     * @param $arguments
     * @return mixed|null
     */
    public function __call($method, $arguments)
    {
        if ($method == '_action') {
            return null;
        }

        return PropertyAccess::createPropertyAccessor()->getValue($this->translate(), $method);
    }

    public function getClassName()
    {
        return __NAMESPACE__ . '\\' . (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned"=true})
     * @Gedmo\Versioned
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="ActivityAreaGalleryImage", mappedBy="activityArea", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $galleryImages;

    /**
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\ProductFeature")
     * @ORM\JoinTable(name="activity_area_has_feature",
     *   joinColumns={@ORM\JoinColumn(name="activity_area_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="product_fature_id", referencedColumnName="id")}
     *  )
     */
    private $features;

    /**
     * @ORM\ManyToMany(targetEntity="FrontendBundle\Entity\Faq", cascade={"persist","remove"})
     * @ORM\JoinTable(name="activity_area_has_faq",
     *   joinColumns={@ORM\JoinColumn(name="activity_area_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="faq_id", referencedColumnName="id", unique=true)}
     *  )
     */
    private $faq;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    public function __toString()
    {
        return $this->translate()->getTitle();
    }

    /**
     * @return array|mixed
     */
    public static function yesOrNo()
    {
        return [
            self::YES => "form.yes",
            self::NO => "form.no"
        ];
    }

    /**
     * @return array|mixed
     */
    public static function yesOrNoForm()
    {
        return [
            "form.yes" => self::YES,
            "form.no" => self::NO,
        ];
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galleryImages = new ArrayCollection();
        $this->features = new ArrayCollection();
        $this->faq = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function hasGalleryImage(ActivityAreaGalleryImage $galleryImage)
    {
        return $this->galleryImages->contains($galleryImage);
    }

    /**
     * Add galleryImage
     *
     * @param \Ecommerce\Entity\ActivityAreaGalleryImage $galleryImage
     *
     * @return Product
     */
    public function addGalleryImage(ActivityAreaGalleryImage $galleryImage)
    {
        if (!$this->hasGalleryImage($galleryImage)) {
            $galleryImage->setActivityArea($this);
            $this->galleryImages->add($galleryImage);
        }

        return $this;
    }

    /**
     * Remove galleryImage
     *
     * @param \Ecommerce\Entity\ActivityAreaGalleryImage $galleryImage
     */
    public function removeGalleryImage(ActivityAreaGalleryImage $galleryImage)
    {
        if ($this->hasGalleryImage($galleryImage)) {
            $this->galleryImages->removeElement($galleryImage);
        }
    }

    /**
     * Get galleryImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryImages()
    {
        return $this->galleryImages;
    }

    public function hasGalleryImages()
    {
        return !$this->getGalleryImages()->isEmpty();
    }

    public function getSlugForUrl()
    {
        $slug = $this->translate($this->getDefaultLocale())->getSlug();

        if ($this->hasCategories()) {
            $strSlug = $this->getCategories()[0]->getSlug() . '/' . $slug;
        } else {
            $strSlug = $slug;
        }

        return $strSlug;
    }

    public function getTitle()
    {
        return $this->translate()->getTitle();
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return Collection|ProductFeature[]
     */
    public function getFeatures(): Collection
    {
        return $this->features;
    }

    public function addFeature(ProductFeature $feature): self
    {
        if (!$this->features->contains($feature)) {
            $this->features[] = $feature;
        }

        return $this;
    }

    public function removeFeature(ProductFeature $feature): self
    {
        if ($this->features->contains($feature)) {
            $this->features->removeElement($feature);
        }

        return $this;
    }

    /**
     * @return Collection|Faq[]
     */
    public function getFaq(): Collection
    {
        return $this->faq;
    }

    public function addFaq(Faq $faq): self
    {
        if (!$this->faq->contains($faq)) {
            $this->faq[] = $faq;
        }

        return $this;
    }

    public function removeFaq(Faq $faq): self
    {
        if ($this->faq->contains($faq)) {
            $this->faq->removeElement($faq);
        }

        return $this;
    }
}
