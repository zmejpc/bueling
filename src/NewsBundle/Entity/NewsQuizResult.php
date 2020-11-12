<?php

namespace NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\__Call\__CallTrait;

/**
 * NewsQuizResult
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="news_quiz_result_table")
 * @ORM\Entity(repositoryClass="NewsBundle\Entity\Repository\NewsQuizResultRepository")
 */
class NewsQuizResult
{
    use IdTrait;

    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\NewsQuizOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_quiz_option_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $quizOption;


    /**
     * @var \NewsBundle\Entity\News
     *
     * @Gedmo\Versioned
     * @ORM\ManyToOne(targetEntity="NewsBundle\Entity\NewsQuiz")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_quiz_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $quiz;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="ip", type="string", length=255, nullable=false)
     */
    private $ip;

    public function getQuizOption()
    {
        return $this->quizOption;
    }

    public function setQuizOption(NewsQuizOption $quizOption): void
    {
        $this->quizOption = $quizOption;
    }

    public function getQuiz(): NewsQuizInterface
    {
        return $this->quiz;
    }

    public function setQuiz(NewsQuiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip): void
    {
        $this->ip = $ip;
    }
}