<?php

namespace StaticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ecommerce\Entity\ProductCategory;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FooterSettings
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="footer_settings_table")
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\FooterSettingsRepository")
 * @author Design studio origami <https://origami.ua>
 */
class FooterSettings
{
    use IdTrait;

    /**
     * @ORM\OneToMany(targetEntity="FooterSocialLink", mappedBy="footerSettings", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $socialLinks;

    /**
     * @ORM\OneToMany(targetEntity="FooterLink", mappedBy="footerSettings", cascade={"persist","remove"}, orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $links;

    /**
     * @ORM\OneToMany(targetEntity="FooterImage", mappedBy="footerSettings", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $images;

    public function __construct()
    {
        $this->socialLinks = new ArrayCollection();
        $this->links = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    /**
     * @return Collection|FooterSocialLink[]
     */
    public function getSocialLinks(): Collection
    {
        return $this->socialLinks;
    }

    public function addSocialLink(FooterSocialLink $socialLink): self
    {
        if (!$this->socialLinks->contains($socialLink)) {
            $this->socialLinks[] = $socialLink;
            $socialLink->setFooterSettings($this);
        }

        return $this;
    }

    public function removeSocialLink(FooterSocialLink $socialLink): self
    {
        if ($this->socialLinks->contains($socialLink)) {
            $this->socialLinks->removeElement($socialLink);
            // set the owning side to null (unless already changed)
            if ($socialLink->getFooterSettings() === $this) {
                $socialLink->setFooterSettings(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FooterLink[]
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function addLink(FooterLink $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setFooterSettings($this);
        }

        return $this;
    }

    public function removeLink(FooterLink $link): self
    {
        if ($this->links->contains($link)) {
            $this->links->removeElement($link);
            // set the owning side to null (unless already changed)
            if ($link->getFooterSettings() === $this) {
                $link->setFooterSettings(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FooterImage[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(FooterImage $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setFooterSettings($this);
        }

        return $this;
    }

    public function removeImage(FooterImage $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getFooterSettings() === $this) {
                $image->setFooterSettings(null);
            }
        }

        return $this;
    }
}
