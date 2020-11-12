<?php

namespace StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * FooterImage
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="footer_image_table")
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\FooterImageRepository")
 * @author Design studio origami <https://origami.ua>
 */
class FooterImage
{
    use IdTrait;
    use ImgTrait;

   /**
     * @ORM\ManyToOne(targetEntity="FooterSettings", inversedBy="images")
     */
    private $footerSettings;

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
