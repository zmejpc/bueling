<?php

namespace NewsBundle\Entity;

use ComponentBundle\Entity\Id\IdInterface;
use ComponentBundle\Entity\Img\ImgInterface;
use ComponentBundle\Entity\Position\PositionInterface;
use ComponentBundle\Entity\ShowOnWebsite\ShowOnWebsiteInterface;
use ComponentBundle\Entity\YesOrNo\YesOrNoInterface;

/**
 * Interface NewsGalleryImageInterface
 * @author Design studio origami <https://origami.ua>
 */
interface NewsGalleryImageInterface extends YesOrNoInterface, IdInterface, PositionInterface, ShowOnWebsiteInterface,
    ImgInterface
{
    /**
     * @return NewsInterface
     */
    public function getNews(): NewsInterface;

    /**
     * @param NewsInterface $news
     */
    public function setNews(NewsInterface $news): void;
}
