<?php

namespace NewsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * NewsQuiz
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_quiz_table", indexes={
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsQuizRepository")
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuiz implements NewsQuizInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use IdTrait;
    use YesOrNoTrait;
    use __CallTrait;
    use ShowOnWebsiteTrait;
    use PositionTrait;

    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\News", inversedBy="newsQuizzes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $news;

    /**
     * @ORM\OneToMany(targetEntity="NewsBundle\Entity\NewsQuizOption", mappedBy="quiz", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $quizOptions;

    /**
     * News constructor.
     */
    public function __construct()
    {
        $this->quizOptions = new ArrayCollection();
    }

    /**
     * @return NewsInterface
     */
    public function getNews(): NewsInterface
    {
        return $this->news;
    }

    /**
     * @param NewsInterface $news
     */
    public function setNews(NewsInterface $news): void
    {
        $this->news = $news;
    }

    /**
     * @param NewsQuizOptionInterface $newsQuizOption
     * @return bool
     */
    public function hasQuizOptions(NewsQuizOptionInterface $newsQuizOption): bool
    {
        return $this->quizOptions->contains($newsQuizOption);
    }

    /**
     * Add newsQuizzes
     *
     * @param NewsQuizOptionInterface $options
     */
    public function addQuizOption(NewsQuizOptionInterface $options): void
    {
        if (!$this->hasQuizOptions($options)) {
            $options->setQuiz($this);
            $this->quizOptions->add($options);
        }
    }

    /**
     * Remove newsQuizOptions
     *
     * @param NewsQuizOptionInterface $options
     */
    public function removeQuizOption(NewsQuizOptionInterface $options): void
    {
        if ($this->hasQuizOptions($options)) {
            $this->quizOptions->removeElement($options);
        }
    }

    /**
     * Get newsQuizOptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizOptions(): Collection
    {
        return $this->quizOptions;
    }
}