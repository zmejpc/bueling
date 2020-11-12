<?php

namespace ComponentBundle\Entity\ShowOnWebsite;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait ShowOnWebsiteTrait
{
    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="show_on_website", type="boolean", nullable=false)
     */
    protected $showOnWebsite = YesOrNoInterface::YES;  # показывать на сайте: 0 - нет, 1 - да

    /**
     * @return bool|null
     */
    public function getShowOnWebsite(): ?bool
    {
        return $this->showOnWebsite;
    }

    /**
     * @param bool $showOnWebsite
     */
    public function setShowOnWebsite(bool $showOnWebsite): void
    {
        $this->showOnWebsite = $showOnWebsite;
    }
}
