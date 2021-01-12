<?php

namespace FrontendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\ActivityArea;
use SeoBundle\Utils\SeoManager;
use Ecommerce\Entity\Project;
use Ecommerce\Entity\Partner;
use NewsBundle\Entity\News;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class HomepageController extends AbstractController
{
    public function indexAction(EntityManagerInterface $em, SeoManager $seoManager)
    {
        $staticContent = $em->getRepository(StaticContent::class)->getByPageForFrontend('homepage');
        $categories = $em->getRepository(ProductCategory::class)->getForFrontend();
        $areas = $em->getRepository(ActivityArea::class)->getForFrontend(4);
        $news = $em->getRepository(News::class)->getLimitRANDElements(3);
        $projects = $em->getRepository(Project::class)->getForHomepage();
        $partners = $em->getRepository(Partner::class)->getForFrontend();

        return $this->render('homepage/index.html.twig', [
            'seo' => $seoManager->getSeoForHomePage(),
            'is_transparent' => true,
            'staticContent' => $staticContent,
            'categories' => $categories,
            'projects' => $projects,
            'partners' => $partners,
            'areas' => $areas,
            'news' => $news,
        ]);
    }
}
