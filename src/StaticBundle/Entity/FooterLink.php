<?php

namespace StaticBundle\Entity;

use Ecommerce\Entity\ProductCategory;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use ComponentBundle\Entity\Position\PositionTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FooterLink
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="footer_link_table")
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\FooterLinkRepository")
 * @author Design studio origami <https://origami.ua>
 */
class FooterLink
{
    use IdTrait;
    use PositionTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Gedmo\Versioned
     * @ORM\Column(name="link", type="string", length=255, nullable=true)
     */
    private $link;

    /**
     * @ORM\ManyToOne(targetEntity="FooterSettings", inversedBy="links")
     */
    private $footerSettings;

    /**
     * @ORM\ManyToOne(targetEntity="Ecommerce\Entity\ProductCategory")
     */
    private $productCategory;

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

    public function getProductCategory(): ?ProductCategory
    {
        return $this->productCategory;
    }

    public function setProductCategory(?ProductCategory $productCategory): self
    {
        $this->productCategory = $productCategory;

        return $this;
    }

}
