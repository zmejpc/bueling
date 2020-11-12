<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\Collection;
use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * ProductCategory
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_category_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ProductCategoryRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ProductCategory implements ProductCategoryInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use SeoTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;

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
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="poster", type="text", nullable=true)
     */
    private $poster;

    /**
     * @var \Ecommerce\Entity\ProductCategory
     *
     * @ORM\ManyToOne(targetEntity="Ecommerce\Entity\ProductCategory", inversedBy="children")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     * })
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Ecommerce\Entity\ProductCategory", mappedBy="parent", cascade={"persist"})
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="slug", type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\Product", mappedBy="categories")
     */
    private $products;

    public function getClassName()
    {
        return __NAMESPACE__ . '\\' . (new \ReflectionClass($this))->getShortName();
    }

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

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->translate()->getTreeTitle();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->products = new ArrayCollection();
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

    public function categoryTree()
    {
        foreach ($this->translations as $key => $item) {
            $parent = $this->getParent();
            $categories = $this->getChildren();
            $result = $item->getTitle();

            if(empty($this->translations[$this->getDefaultLocale()])){
                $slug = $this->translations['ru']->getSlug();
            }else{
                $slug = $this->translations[$this->getDefaultLocale()]->getSlug();
            }

            while ($parent != null) {
                $parentTranslations = $parent->getTranslations();
                $slug = $parentTranslations[$parent->getDefaultLocale()]->getSlug() . '/' . $slug;

                if (count($parentTranslations) > 0 and $parentTranslations[$key]) {
                    $result = $parentTranslations[$key]->getTitle() . ' > ' . $result;
                }

                $parent = $parent->getParent();
            }

            $this->translate($key)->setTreeTitle($result);
            $this->setSlug($slug);

            $this->setChildTreeTitleAndSlug($categories, $key, $slug, $result);
        }

        return $this;
    }

    public function setChildTreeTitleAndSlug($categories, $key, $slug, $treeTitle)
    {
        foreach ($categories as $category) {
            $translateByKey = $category->translate($key);
            $category->setSlug($slug . '/' . $category->translate($category->getDefaultLocale())->getSlug());
            $translateByKey->setTreeTitle($treeTitle . '>' . $translateByKey->getTitle());
            $children = $category->getChildren();
            $this->setChildTreeTitleAndSlug($children, $key, $category->getSlug(), $translateByKey->getTreeTitle());
        }
    }

    /**
     * @param ProductCategory $category
     * @param $arr
     * @return mixed|void
     */
    public function getParents(ProductCategory $category, &$arr)
    {
        $arr[] = $category;

        $parent = $category->getParent();
        if (!is_null($parent)) {
            $this->getParents($parent, $arr);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getAncestors()
    {
        $ancestors = [];

        for ($ancestor = $this->getParent(); null !== $ancestor; $ancestor = $ancestor->getParent()) {
            $ancestors[] = $ancestor;
        }

        return new ArrayCollection($ancestors);
    }

    /**
     * Set parent
     *
     * @param \Ecommerce\Entity\ProductCategory $parent
     *
     * @return ProductCategory
     */
    public function setParent(ProductCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Ecommerce\Entity\ProductCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add child
     *
     * @param \Ecommerce\Entity\ProductCategory $child
     *
     * @return ProductCategory
     */
    public function addChild(ProductCategory $child)
    {
        if (!$this->hasChild($child)) {
            $this->children->add($child);
        }

        if ($this !== $child->getParent()) {
            $child->setParent($this);
        }

        return $this;
    }

    /**
     * @param ProductCategory $productCategory
     * @return bool
     */
    public function hasChild(ProductCategory $productCategory)
    {
        return $this->children->contains($productCategory);
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !$this->children->isEmpty();
    }

    /**
     * Remove child
     *
     * @param \Ecommerce\Entity\ProductCategory $child
     */
    public function removeChild(ProductCategory $child)
    {
        if ($this->hasChild($child)) {
            $child->setParent(null);

            $this->children->removeElement($child);
        }
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return string
     */
    public function getPoster(): ?string
    {
        return $this->poster;
    }

    /**
     * @param string $poster
     */
    public function setPoster(string $poster): void
    {
        $this->poster = $poster;
    }

    /**
     * @return string
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getSlugForUrl()
    {
        $slug = $this->translate($this->getDefaultLocale())->getSlug();

        if ($this->getParent()) {
            $strSlug = $this->getParent()->translate($this->getDefaultLocale())->getSlug() . '/' . $slug;
        } else {
            $strSlug = $slug;
        }

        return $strSlug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getChildrenIds()
    {
        $ids = array_column((array)$this->getChildren(), 'id');

        foreach($this->getChildren() as $child)
            $ids = array_merge($ids, $child->getChildrenIds());

        return $ids;
    }

    public function hasChecked(array $ids)
    {
        foreach($ids as $id)
            if(in_array($id, $this->getChildrenIds()))
                return true;

        return false;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeCategory($this);
        }

        return $this;
    }
}
