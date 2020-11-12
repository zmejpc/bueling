<?php

namespace NewsBundle\Controller\Frontend;

use NewsBundle\Entity\News;
use NewsBundle\Entity\NewsCategory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * @author Design studio origami <https://origami.ua>
 */
trait newsSiteMap
{
    public function generateSiteMap(EntityManagerInterface $em, string $defaultLocale, RouterInterface $router)
    {
        $urls[] = [
            'loc' => $router->generate('frontend_news_list'),
            'changefreq' => 'weekly',
            'priority' => '1'
        ];

        $newsCategories = $em->getRepository(NewsCategory::class)->getElementsForSiteMap();
        foreach ($newsCategories as $item) {
            $elementSlug = $item['translations'][$defaultLocale]['slug'];
            $urls[] = [
                'loc' => $router->generate('frontend_news_category_show', [
                    'category' => $elementSlug
                ]),
                'changefreq' => 'weekly',
                'priority' => '1'
            ];
        }

        $news = $em->getRepository(News::class)->getElementsForSiteMap();
        foreach ($news as $item) {
            $elementSlug = $item['translations'][$defaultLocale]['slug'];
            $newsCategory = $item['newsCategory'];
            if (!is_null($newsCategory)) {
                $newsCategorySlug = $newsCategory['translations'][$defaultLocale]['slug'];
                $urls[] = [
                    'loc' => $router->generate('frontend_news_show_with_category', [
                        'slug' => $elementSlug,
                        'category' => $newsCategorySlug,
                    ]),
                    'changefreq' => 'weekly',
                    'priority' => '1'
                ];
            } else {
                $urls[] = [
                    'loc' => $router->generate('frontend_news_show', [
                        'slug' => $elementSlug,
                    ]),
                    'changefreq' => 'weekly',
                    'priority' => '1'
                ];
            }
        }

        return $urls;
    }
}