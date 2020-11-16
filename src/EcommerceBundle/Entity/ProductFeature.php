<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * ProductFeature
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product_feature_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity(repositoryClass="Ecommerce\Entity\Repository\ProductFeatureRepository")
 * @author Design studio origami <https://origami.ua>
 */
class ProductFeature
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

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

    public function getClassName()
    {
        return __NAMESPACE__ . '\\' . (new \ReflectionClass($this))->getShortName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }
}
