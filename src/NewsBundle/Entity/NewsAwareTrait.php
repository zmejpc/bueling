<?php

namespace NewsBundle\Entity;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait NewsAwareTrait
{
    /**
     * @return NewsInterface
     */
    public function getNews(): NewsInterface
    {
        return $this->news;
    }

    /**
     * @param NewsInterface $news
     */
    public function setNews(?NewsInterface $news): void
    {
        $this->news = $news;
    }
}