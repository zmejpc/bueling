<?php

namespace NewsBundle\Entity;

use SeoBundle\Entity\SeoTraitInterface;
use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Poster\PosterInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;

/**
 * Interface NewsAuthorInterface
 * @package NewsBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
interface NewsAuthorInterface extends NewsCollectionAwareInterface, IdInterface,
    PositionInterface, ShowOnWebsiteInterface, SeoTraitInterface, PosterInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * NewsCategoryInterface constructor.
     */
    public function __construct();
}
