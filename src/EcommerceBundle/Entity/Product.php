<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\Collection;
use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * Product
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="product_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="seo_UNIQUE", columns={"seo_id"})
 * }, indexes={
 *     @ORM\Index(name="seo_idx", columns={"seo_id", "entity_class"}),
 *     @ORM\Index(name="position_idx", columns={"position", "entity_class"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website", "entity_class"}),
 *     @ORM\Index(name="price_idx", columns={"price"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ProductRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class Product
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
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\ProductCategory", inversedBy="products")
     * @ORM\JoinTable(name="product_has_product_category",
     *   joinColumns={
     *     @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="CASCADE")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="product_category_id", referencedColumnName="id", onDelete="CASCADE")
     *   }
     * )
     */
    private $categories;

    /**
     * @ORM\OneToMany(targetEntity="ProductGalleryImage", mappedBy="product", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $galleryImages;

    /**
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\ProductFeature")
     * @ORM\JoinTable(name="product_has_feature",
     *   joinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="product_fature_id", referencedColumnName="id")}
     *  )
     */
    private $features;

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="top_sale", type="integer", options="{default: 0}")
     */
    protected $topSale = self::NO;

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="is_nova", type="integer", nullable=false)
     */
    protected $isNova = self::NO;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="price", type="decimal", precision=11, scale=0, nullable=true)
     */
    private $price;

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
        $this->categories = new ArrayCollection();
        $this->features = new ArrayCollection();
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

    public function hasProductCategory(ProductCategory $category)
    {
        return $this->categories->contains($category);
    }

    /**
     * Add category
     *
     * @param \Ecommerce\Entity\ProductCategory $category
     *
     * @return Product
     */
    public function addCategory(ProductCategory $category)
    {
        if (!$this->hasProductCategory($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    /**
     * Remove category
     *
     * @param \Ecommerce\Entity\ProductCategory $category
     */
    public function removeCategory(ProductCategory $category)
    {
        if ($this->hasProductCategory($category)) {
            $this->categories->removeElement($category);
        }
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function hasCategories()
    {
        return !$this->getCategories()->isEmpty();
    }

    public function hasGalleryImage(ProductGalleryImage $galleryImage)
    {
        return $this->galleryImages->contains($galleryImage);
    }

    /**
     * Add galleryImage
     *
     * @param \Ecommerce\Entity\ProductGalleryImage $galleryImage
     *
     * @return Product
     */
    public function addGalleryImage(ProductGalleryImage $galleryImage)
    {
        if (!$this->hasGalleryImage($galleryImage)) {
            $galleryImage->setProduct($this);
            $this->galleryImages->add($galleryImage);
        }

        return $this;
    }

    /**
     * Remove galleryImage
     *
     * @param \Ecommerce\Entity\ProductGalleryImage $galleryImage
     */
    public function removeGalleryImage(ProductGalleryImage $galleryImage)
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

    /**
     * @return int
     */
    public function getTopSale(): bool
    {
        return $this->topSale;
    }

    /**
     * @param bool $topSale
     */
    public function setTopSale(bool $topSale): void
    {
        $this->topSale = $topSale;
    }

    /**
     * @return int
     */
    public function getIsNova(): bool
    {
        return $this->isNova;
    }

    /**
     * @param bool $isNova
     */
    public function setIsNova(bool $isNova): void
    {
        $this->isNova = $isNova;
    }

    /**
     * @return float
     */
    public function getPrice(): ?float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price): void
    {
        $this->price = (float)$price;
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
}
