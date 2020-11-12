<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use ComponentBundle\Entity\Id\IdTrait;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * NewsCategory
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="news_category_table", indexes={
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsCategoryRepository")
 * @author Design studio origami <https://origami.ua>
 */
class NewsCategory implements NewsCategoryInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use SeoTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;
    use NewsCollectionAwareTrait;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\News", mappedBy="newsCategory", cascade={"persist","remove"})
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $news;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="old_slug", type="string", length=255, nullable=true, unique=true)
     */
    private $oldSlug;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->news = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }

    /**
     * @return bool|null
     */
    public function getShowInMenu(): ?bool
    {
        return $this->showInMenu;
    }

    /**
     * @param bool $showInMenu
     * @return NewsCategoryInterface
     */
    public function setShowInMenu(bool $showInMenu): NewsCategoryInterface
    {
        $this->showInMenu = $showInMenu;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getShowOnMainPage(): ?bool
    {
        return $this->showOnMainPage;
    }

    /**
     * @param bool $showOnMainPage
     * @return NewsCategoryInterface
     */
    public function setShowOnMainPage(bool $showOnMainPage): NewsCategoryInterface
    {
        $this->showOnMainPage = $showOnMainPage;

        return $this;
    }

    /**
     * @return string
     */
    public function getOldSlug(): ?string
    {
        return $this->oldSlug;
    }

    /**
     * @param string $oldSlug
     */
    public function setOldSlug(string $oldSlug): void
    {
        $this->oldSlug = $oldSlug;
    }
}