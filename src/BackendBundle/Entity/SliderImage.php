<?php

namespace BackendBundle\Entity;

use ComponentBundle\Entity\__Call\__CallInterface;
use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\ORM\Mapping as ORM;
use BackendBundle\Entity\Slider;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * SliderImage
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="slider_image_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity(repositoryClass="BackendBundle\Entity\Repository\SliderImageRepository")
 * @author Design studio origami <https://origami.ua>
 */
class SliderImage
{
    use ORMBehaviors\Timestampable\Timestampable;
    use ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use __CallTrait;
    use YesOrNoTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;

    /**
     * @var \BackendBundle\Entity\Slider
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="BackendBundle\Entity\Slider", inversedBy="sliderImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="slider_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $slider;

    /**
     * @ORM\Column(name="link", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $link;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

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
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink(?string $link): void
    {
        $this->link = $link;
    }

    public function getSlider(): ?Slider
    {
        return $this->slider;
    }

    public function setSlider(?Slider $slider): self
    {
        $this->slider = $slider;

        return $this;
    }
}