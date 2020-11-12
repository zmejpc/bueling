<?php

namespace NewsBundle\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Trait NewsAwareTrait
 * @package NewsBundle\Entity
 * @author Design studio origami <https://origami.ua>
 */
trait NewsCollectionAwareTrait
{
    /**
     * @param News $news
     * @return bool
     */
    public function hasNews(NewsInterface $news): bool
    {
        return $this->news->contains($news);
    }
    
    /**
     * @return Collection
     */
    public function getNews(): Collection
    {
        return $this->news;
    }

    /**
     * @param NewsInterface $news
     */
    public function addNews(NewsInterface $news): void
    {
        if (!$this->news->contains($news)) {
            $this->news->add($news);
        }
    }

    /**
     * @param NewsInterface $news
     */
    public function removeNews(NewsInterface $news): void
    {
        if ($this->news->contains($news)) {
            $this->news->removeElement($news);
        }
    }
}