<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use ComponentBundle\Entity\Link\LinkTrait;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Link\LinkInterface;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * NewsElement
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="news_element_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity
 * @author Design studio origami <https://origami.ua>
 */
class NewsElement implements IdInterface, PositionInterface, ShowOnWebsiteInterface,
    LinkInterface, NewsAwareInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;
    use LinkTrait;
    use NewsAwareTrait;
    use ImgTrait;

    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\News", inversedBy="elements")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $news;
}