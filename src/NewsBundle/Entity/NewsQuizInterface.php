<?php

namespace NewsBundle\Entity;

use Doctrine\Common\Collections\Collection;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\__Call\__CallInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsQuizInterface extends IdInterface, ShowOnWebsiteInterface, YesOrNoInterface, __CallInterface,
    PositionInterface
{
    /**
     * @param NewsQuizOptionInterface $newsQuizOption
     * @return bool
     */
    public function hasQuizOptions(NewsQuizOptionInterface $newsQuizOption): bool;

    /**
     * Add newsQuizzes
     *
     * @param NewsQuizOptionInterface $options
     */
    public function addQuizOption(NewsQuizOptionInterface $options): void;

    /**
     * Remove newsQuizOptions
     *
     * @param NewsQuizOptionInterface $options
     */
    public function removeQuizOption(NewsQuizOptionInterface $options): void;

    /**
     * Get newsQuizOptions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getQuizOptions(): Collection;
}