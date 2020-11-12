<?php

namespace StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FooterSocialLink
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="footer_social_link_table")
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\FooterSocialLinkRepository")
 * @author Design studio origami <https://origami.ua>
 */
class FooterSocialLink
{
    use IdTrait;
    use ImgTrait;
    use PositionTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="link", type="string", length=255, nullable=false)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="FooterSettings", inversedBy="socialLinks")
     */
    private $footerSettings;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getFooterSettings(): ?FooterSettings
    {
        return $this->footerSettings;
    }

    public function setFooterSettings(?FooterSettings $footerSettings): self
    {
        $this->footerSettings = $footerSettings;

        return $this;
    }
}
