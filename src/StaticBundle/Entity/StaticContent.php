<?php

namespace StaticBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\Id\IdTrait;
use ComponentBundle\Entity\Img\ImgTrait;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints as UniqueEntity;

/**
 * StaticContent
 *
 * @Gedmo\Loggable
 * @ORM\Table(name="static_content_table", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="UNIQUE", columns={"link_name", "page"})
 * }, indexes={
 *     @ORM\Index(name="link_name_idx", columns={"link_name", "page"}),
 *     })
 * @UniqueEntity\UniqueEntity(fields={"linkName", "page"})
 * @ORM\Entity(repositoryClass="StaticBundle\Entity\Repository\StaticContentRepository")
 * @author Design studio origami <https://origami.ua>
 */
class StaticContent implements StaticContentInterface
{
    use ORMBehaviors\Translatable\Translatable,
        ORMBehaviors\Timestampable\Timestampable;
    use IdTrait;
    use ImgTrait;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="link_name", type="string", length=255, nullable=false)
     */
    private $linkName;

    /**
     * @var string
     *
     * @Gedmo\Versioned
     * @Assert\NotBlank()
     * @ORM\Column(name="page", type="string", length=255, nullable=false)
     */
    private $page;

    /**
     * @return string
     */
    public function getLinkName(): ?string
    {
        return $this->linkName;
    }

    /**
     * @param string $linkName
     */
    public function setLinkName(string $linkName): void
    {
        $this->linkName = $linkName;
    }

    /**
     * @return string
     */
    public function getPage(): ?string
    {
        return $this->page;
    }

    /**
     * @param string $page
     */
    public function setPage(string $page): void
    {
        $this->page = $page;
    }
}
