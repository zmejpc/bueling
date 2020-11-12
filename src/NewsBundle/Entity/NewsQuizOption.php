<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\__Call\__CallTrait;
use ComponentBundle\Entity\YesOrNo\YesOrNoTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteTrait;

/**
 * NewsQuizOption
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_quiz_option_table", indexes={
 *     @ORM\Index(name="show_on_website_idx", columns={"show_on_website"}),
 *     })
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsQuizOptionRepository")
 * @author Design studio origami <https://origami.ua>
 */
class NewsQuizOption implements NewsQuizOptionInterface
{
    use ORMBehaviors\Timestampable\Timestampable,
        ORMBehaviors\Translatable\Translatable;

    use IdTrait;
    use __CallTrait;
    use YesOrNoTrait;
    use PositionTrait;
    use ShowOnWebsiteTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="votes", type="integer", nullable=false)
     * */
    private $votes = 0;

    /**
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\NewsQuiz", inversedBy="quizOptions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="quiz_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $quiz;

    /**
     * @return int
     */
    public function getVotes(): int
    {
        return $this->votes;
    }

    /**
     * @param int $votes
     */
    public function setVotes(int $votes): void
    {
        $this->votes = $votes;
    }

    /**
     * @return NewsQuizInterface
     */
    public function getQuiz(): NewsQuizInterface
    {
        return $this->quiz;
    }

    /**
     * @param NewsQuizInterface $quiz
     */
    public function setQuiz(NewsQuizInterface $quiz): void
    {
        $this->quiz = $quiz;
    }
}
