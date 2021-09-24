<?php

namespace Ecommerce\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\Poster\PosterTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * SmartLink
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="smart_link_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position", "entity_class"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website", "entity_class"}),
 *     })
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\SmartLinkRepository")
 * @ORM\MappedSuperclass
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="entity_class", type="string")
 * @author Design studio origami <https://origami.ua>
 */
class SmartLink
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use PositionTrait;
    use ShowOnWebsiteTrait;

    public const YES = 1;
    public const NO = 0;

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
     * @ORM\ManyToMany(targetEntity="Ecommerce\Entity\Product", mappedBy="smartLinks")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getClassName()
    {
        return __NAMESPACE__ . '\\' . (new \ReflectionClass($this))->getShortName();
    }

    /**
     * @return Collection|SmartLink[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(SmartLink $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->addSmartLink($this);
        }

        return $this;
    }

    public function removeProduct(SmartLink $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            $product->removeSmartLink($this);
        }

        return $this;
    }
}
