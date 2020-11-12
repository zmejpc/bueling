<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Img\ImgInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * NewsGalleryImage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_gallery_image_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsGalleryImage implements IdInterface, PositionInterface,
    ShowOnWebsiteInterface, ImgInterface, NewsAwareInterface
{
    use ORMBehaviors\Timestampable\Timestampable;
    use IdTrait;
    use ImgTrait;
    use PositionTrait;
    use NewsAwareTrait;
    use ShowOnWebsiteTrait;

    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\News", inversedBy="galleryImages")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $news;
}
