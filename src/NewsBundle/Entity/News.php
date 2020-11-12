<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Video\VideoTrait;
use ComponentBundle\Entity\Poster\PosterTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * News
 *
 * @Gedmo\Loggable
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="news_table", indexes={
 *     @ORM\Index(name="news_category_id_idx", columns={"news_category_id"}),
 *     @ORM\Index(name="position_idx", columns={"position"}),
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"})
 * })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsRepository")
 * @author Design studio origami <https://origami.ua>
 */
class News implements NewsInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;
    use IdTrait;
    use SeoTrait;
    use PosterTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;
    use VideoTrait;

    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="is_main", type="boolean", nullable=false)
     */
    protected $isMain = YesOrNoInterface::NO;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="old_slug", type="string", length=255, nullable=true, unique=true)
     */
    private $oldSlug;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\NewsCategory", inversedBy="news")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_category_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $newsCategory;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="publish_at", type="datetime", nullable=false)
     * @Gedmo\Versioned
     */
    private $publishAt;

    /**
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\NewsQuiz", mappedBy="news", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $newsQuizzes;

    /**
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\NewsComment", mappedBy="news", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\NewsGalleryImage", mappedBy="news", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "DESC"})
     */
    private $galleryImages;

    /**
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\NewsElement", mappedBy="news", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $elements;

    /**
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\NewsAuthor", inversedBy="news")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_author_id", referencedColumnName="id", onDelete="SET NULL")
     * })
     */
    private $newsAuthor;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="NewsTag", cascade={"persist","remove"}, inversedBy="news")
     * @ORM\JoinTable(name="news_has_news_tag_table",
     *   joinColumns={
     *     @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="tag_id", referencedColumnName="id")
     *   }
     * )
     */
    private $tags;

    /**
     * @var int
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="show_only_on_author_page", type="integer", nullable=true)
     */
    protected $showOnlyOnAuthorPage = self::NO; # показывать на сайте: 0 - нет, 1 - да

    /**
     * @var integer
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="views", type="integer", nullable=false)
     */
    protected $views = 0;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="signature_url", type="text", nullable=true)
     */
    private $signatureUrl;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->galleryImages = new ArrayCollection();
        $this->elements = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->newsQuizzes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getNewsAuthor(): ?NewsAuthor
    {
        return $this->newsAuthor;
    }

    /**
     * @return mixed
     */
    public function getNewsQuizzes(): Collection
    {
        return $this->newsQuizzes;
    }

    /**
     * @return bool
     */
    public function getShowOnlyOnAuthorPage(): bool
    {
        return $this->showOnlyOnAuthorPage;
    }

    /**
     * @param bool $showOnlyOnAuthorPage
     */
    public function setShowOnlyOnAuthorPage(bool $showOnlyOnAuthorPage): void
    {
        $this->showOnlyOnAuthorPage = $showOnlyOnAuthorPage;
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

    /**
     * @param mixed $newsAuthor
     */
    public function setNewsAuthor(?NewsAuthor $newsAuthor): void
    {
        $this->newsAuthor = $newsAuthor;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->translate()->getTitle();
    }

    /**
     * @return \DateTimeInterface|null
     * @throws \Exception
     */
    public function getPublishAt(): ?\DateTimeInterface
    {
        if (!$this->publishAt) {
            self::setPublishAt(null);
        }

        return $this->publishAt;
    }

    /**
     * @param \DateTimeInterface|null $publishAt
     * @return NewsInterface
     * @throws \Exception
     */
    public function setPublishAt(?\DateTimeInterface $publishAt): NewsInterface
    {
        if (is_null($publishAt)) {
            $this->publishAt = new \DateTime();
        } else {
            $this->publishAt = $publishAt;
        }

        return $this;
    }

    /**
     * @return NewsCategory|null
     */
    public function getNewsCategory(): ?NewsCategory
    {
        return $this->newsCategory;
    }

    /**
     * @param NewsCategoryInterface|null $newsCategory
     * @return NewsInterface
     */
    public function setNewsCategory(?NewsCategoryInterface $newsCategory): NewsInterface
    {
        $this->newsCategory = $newsCategory;

        return $this;
    }

    /**
     * @param NewsGalleryImage $galleryImage
     * @return bool
     */
    public function hasGalleryImage(NewsGalleryImage $galleryImage): bool
    {
        return $this->galleryImages->contains($galleryImage);
    }

    /**
     * Add galleryImage
     *
     * @param NewsGalleryImage $galleryImage
     */
    public function addGalleryImage(NewsGalleryImage $galleryImage): void
    {
        if (!$this->hasGalleryImage($galleryImage)) {
            $galleryImage->setNews($this);
            $this->galleryImages->add($galleryImage);
        }
    }

    /**
     * Remove galleryImage
     *
     * @param NewsGalleryImage $galleryImage
     */
    public function removeGalleryImage(NewsGalleryImage $galleryImage): void
    {
        if ($this->hasGalleryImage($galleryImage)) {
            $this->galleryImages->removeElement($galleryImage);
        }
    }

    /**
     * Get galleryImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGalleryImages(): Collection
    {
        return $this->galleryImages;
    }

    /**
     * @return bool
     */
    public function hasGalleryImages(): bool
    {
        return !$this->getGalleryImages()->isEmpty();
    }

    /**
     * @return Collection|NewsElement[]
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    /**
     * @param NewsElement $element
     * @return NewsInterface
     */
    public function addElement(NewsElement $element): NewsInterface
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setNews($this);
        }

        return $this;
    }

    /**
     * @param NewsElement $element
     * @return NewsInterface
     */
    public function removeElement(NewsElement $element): NewsInterface
    {
        if ($this->elements->contains($element)) {
            $this->elements->removeElement($element);
            // set the owning side to null (unless already changed)
            if ($element->getNews() === $this) {
                $element->setNews(null);
            }
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isMain(): ?bool
    {
        return $this->isMain;
    }

    /**
     * @param bool $isMain
     */
    public function setIsMain(bool $isMain): void
    {
        $this->isMain = $isMain;
    }

    /**
     * @return int
     */
    public function getViews(): int
    {
        return $this->views;
    }

    /**
     * @param int $views
     */
    public function setViews(int $views): void
    {
        $this->views = $views;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param NewsTag $tag
     */
    public function addTags(NewsTag $tag): void
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }
    }

    /**
     * @param NewsTag $tag
     */
    public function removeTags(NewsTag $tag): void
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }
    }

    /**
     * @return string
     */
    public function getSignatureUrl(): ?string
    {
        return $this->signatureUrl;
    }

    /**
     * @param string $signatureUrl
     */
    public function setSignatureUrl(?string $signatureUrl): void
    {
        $this->signatureUrl = $signatureUrl;
    }
}