<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * ActivityAreaGalleryImage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="activity_area_gallery_image_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class ActivityAreaGalleryImage
{
    use ORMBehaviors\Timestampable\Timestampable;
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
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var \Ecommerce\Entity\ActivityArea
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="Ecommerce\Entity\ActivityArea", inversedBy="galleryImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activity_area_id", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $activityArea;

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
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return ActivityArea
     */
    public function getActivityArea(): ActivityArea
    {
        return $this->activityArea;
    }

    /**
     * @param ActivityArea $activityArea
     */
    public function setActivityArea(ActivityArea $activityArea): void
    {
        $this->activityArea = $activityArea;
    }
}
