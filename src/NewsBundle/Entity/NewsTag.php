<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Id\IdInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * NewsTag
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_tag_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsTagRepository")
 * @author Design studio origami <https://origami.ua>
 */
class NewsTag implements IdInterface, PositionInterface,
    ShowOnWebsiteInterface, NewsCollectionAwareInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;
    use NewsCollectionAwareTrait;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="News", mappedBy="tags")
     */
    private $news;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->translate()->getTitle();
    }
}
