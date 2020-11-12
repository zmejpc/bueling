<?php

namespace NewsBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
interface NewsAwareInterface
{
    /**
     * @return NewsInterface
     */
    public function getNews(): NewsInterface;

    /**
     * @param NewsInterface $news
     */
    public function setNews(?NewsInterface $news): void;
}