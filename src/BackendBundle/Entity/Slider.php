<?php

namespace BackendBundle\Entity;

use ComponentBundle\Entity\__Call\__CallInterface;
use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Slider
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="slider_table", uniqueConstraints={
 *     }, indexes={
@ORM\Index(name="position_idx", columns={"position"}),
@ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity(repositoryClass="BackendBundle\Entity\Repository\SliderRepository")
 * @author Design studio origami <https://origami.ua>
 */
class Slider implements YesOrNoInterface, IdInterface, __CallInterface, PositionInterface, ShowOnWebsiteInterface
{
    use ORMBehaviors\Timestampable\Timestampable;
    use IdTrait;
    use __CallTrait;
    use YesOrNoTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;

    /**
     * @ORM\Column(name="title", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @ORM\Column(name="system_name", type="string", nullable=true)
     * @Gedmo\Versioned
     */
    private $systemName;

    /**
     * @ORM\OneToMany(targetEntity="BackendBundle\Entity\SliderImage", mappedBy="slider", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $sliderImages;


    public function __construct()
    {
        $this->sliderImages = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string)$this->getTitle();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(?string $systemName): self
    {
        $this->systemName = $systemName;

        return $this;
    }

    /**
     * @return Collection|SliderImage[]
     */
    public function getSliderImages(): Collection
    {
        return $this->sliderImages;
    }

    public function addSliderImage(SliderImage $sliderImage): self
    {
        if (!$this->sliderImages->contains($sliderImage)) {
            $this->sliderImages[] = $sliderImage;
            $sliderImage->setSlider($this);
        }

        return $this;
    }

    public function removeSliderImage(SliderImage $sliderImage): self
    {
        if ($this->sliderImages->contains($sliderImage)) {
            $this->sliderImages->removeElement($sliderImage);
            // set the owning side to null (unless already changed)
            if ($sliderImage->getSlider() === $this) {
                $sliderImage->setSlider(null);
            }
        }

        return $this;
    }
}
