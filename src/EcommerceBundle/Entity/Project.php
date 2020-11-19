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
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * Project
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="project_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="seo_UNIQUE", columns={"seo_id"})
 * }, indexes={
 *     @ORM\Index(name="seo_idx", columns={"seo_id"}),
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ProjectRepository")
 * @author Design studio origami <https://origami.ua>
 */
class Project
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use SeoTrait;
    use PositionTrait;
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
     * @ORM\OneToMany(targetEntity="ProjectGalleryImage", mappedBy="project", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $galleryImages;

    /**
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\ActivityArea")
     * @ORM\JoinTable(name="project_has_activity_area",
     *   joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="activity_area_id", referencedColumnName="id")}
     *  )
     */
    private $activityAreas;

    /**
     * @ORM\ManyToMany(targetEntity="FrontendBundle\Entity\Faq", cascade={"persist","remove"})
     * @ORM\JoinTable(name="project_has_faq",
     *   joinColumns={@ORM\JoinColumn(name="project_id", referencedColumnName="id")},
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

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="show_on_homepage", type="boolean", nullable=false)
     */
    protected $showOnHomepage = self::NO;

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
        $this->activityAreas = new ArrayCollection();
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

    public function hasGalleryImage(ProjectGalleryImage $galleryImage)
    {
        return $this->galleryImages->contains($galleryImage);
    }

    /**
     * Add galleryImage
     *
     * @param \Ecommerce\Entity\ProjectGalleryImage $galleryImage
     *
     * @return Product
     */
    public function addGalleryImage(ProjectGalleryImage $galleryImage)
    {
        if (!$this->hasGalleryImage($galleryImage)) {
            $galleryImage->setProject($this);
            $this->galleryImages->add($galleryImage);
        }

        return $this;
    }

    /**
     * Remove galleryImage
     *
     * @param \Ecommerce\Entity\ProjectGalleryImage $galleryImage
     */
    public function removeGalleryImage(ProjectGalleryImage $galleryImage)
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
        return $this->translate($this->getDefaultLocale())->getSlug();
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
     * @return Collection|ActivityArea[]
     */
    public function getActivityAreas(): Collection
    {
        return $this->activityAreas;
    }

    public function addActivityArea(ActivityArea $activityArea): self
    {
        if (!$this->activityAreas->contains($activityArea)) {
            $this->activityAreas[] = $activityArea;
        }

        return $this;
    }

    public function removeActivityArea(ActivityArea $activityArea): self
    {
        if ($this->activityAreas->contains($activityArea)) {
            $this->activityAreas->removeElement($activityArea);
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

    /**
     * @return bool|null
     */
    public function getShowOnHomepage(): ?bool
    {
        return $this->showOnHomepage;
    }

    /**
     * @param bool $showOnHomepage
     */
    public function setShowOnHomepage(bool $showOnHomepage): void
    {
        $this->showOnHomepage = $showOnHomepage;
    }
}
