<?php

namespace NewsBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use ComponentBundle\Entity\Position\PositionTrait;

/**
 * NewsComment
 * @Gedmo\Loggable
 * @ORM\Table(name="news_comment_table")
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsCommentRepository")
 */
class NewsComment
{
    use ORMBehaviors\Timestampable\Timestampable;
    use IdTrait;
    use ShowOnWebsiteTrait;
    use YesOrNoTrait;
    use PositionTrait;

    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\News", inversedBy="comments")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $news;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="content", type="text", nullable=true)
     * @Assert\NotBlank
     */
    private $content;

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
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(?string $content): void
    {
        $this->content = $content;
    }
}