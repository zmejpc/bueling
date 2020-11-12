<?php

namespace StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * StaticPageGalleryImage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="static_page_gallery_image_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class StaticPageGalleryImage
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
     * @var StaticBundle\Entity\StaticPage
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="StaticBundle\Entity\StaticPage", inversedBy="galleryImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="static_page_id", referencedColumnName="id", onDelete="cascade")
     * })
     */
    private $staticPage;

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
     * @return StaticPage
     */
    public function getStaticPage(): StaticPage
    {
        return $this->staticPage;
    }

    /**
     * @param StaticPage $staticPage
     */
    public function setStaticPage(StaticPage $staticPage): void
    {
        $this->staticPage = $staticPage;
    }
}
