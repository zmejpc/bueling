<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Poster\PosterTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Doctrine\Common\Collections\ArrayCollection;
use ComponentBundle\Entity\Position\PositionTrait;
use Symfony\Component\PropertyAccess\PropertyAccess;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * NewsAuthor
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="news_author_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 *     })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsAuthorRepository")
 * @author Design studio origami <https://origami.ua>
 */
class NewsAuthor implements NewsAuthorInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use SeoTrait;
    use PosterTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;
    use NewsCollectionAwareTrait;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\News", mappedBy="newsAuthor", cascade={"persist"})
     */
    private $news;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="poster", type="text", length=65535, nullable=true)
     */
    private $poster;

    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
    }
}