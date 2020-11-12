<?php

namespace FrontendBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\Product;
use SeoBundle\Utils\SeoManager;
use StaticBundle\Entity\StaticContent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class HomepageController extends AbstractController
{
    public function indexAction(EntityManagerInterface $em, SeoManager $seoManager)
    {
        $static = $em->getRepository(StaticContent::class)->getByPage('homepage');

        $staticContent = [];
        foreach ($static as $item) {
            $staticContent[$item->getLinkName()]['text'] = $item->translate()->getDescription();
            $staticContent[$item->getLinkName()]['poster'] = $item->getImg();
        }

        $products = $em->getRepository(Product::class)->getTopSaleProductsElements();

        return $this->render('homepage/index.html.twig', [
            'seo' => $seoManager->getSeoForHomePage(),
            'staticContent' => $staticContent,
            'products' => $products,
        ]);
    }
}
