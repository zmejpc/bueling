<?php

namespace ComponentBundle\Entity\IsEnable;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait IsEnableTrait
{
    /**
     * @var boolean
     *
     * @Gedmo\Versioned
     * @ORM\Column(name="is_enable", type="boolean", nullable=false)
     */
    private $isEnable = YesOrNoInterface::YES;

    /**
     * @return bool
     */
    public function getIsEnable(): bool
    {
        return $this->isEnable;
    }

    /**
     * @param bool $isEnable
     */
    public function setIsEnable(bool $isEnable): void
    {
        $this->isEnable = $isEnable;
    }
}