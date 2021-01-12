<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Ecommerce\Entity\ActivityArea;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ActivityAreaController extends AbstractController
{
    public function listAction(EntityManagerInterface $em, TranslatorInterface $translator, BreadcrumbsGenerator $breadcrumbsGenerator)
    {
        $breadcrumbsArr = [];
        $seoHomepage = $em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_product_show'][] = [
            'parameters' => [
                'slug' => $product->translate()->getSlug()
            ],
            'title' => $product->translate()->getTitle(),
        ];

        return $this->render('activity_area/index.html.twig', [
            'seo' => $seo,
            'areas' => $em->getRepository(ActivityArea::class)->getForFrontend(),
            'breadcrumbs' => $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }

    public function showAction(EntityManagerInterface $em, TranslatorInterface $translator, BreadcrumbsGenerator $breadcrumbsGenerator, string $slug)
    {
        $area = $em->getRepository(Product::class)->getProductBySlug($slug);

        if (!$area) {
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $seo = $area->getSeo()->getSeoForPage();

        $breadcrumbsArr = [];
        $seoHomepage = $em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr['frontend_product_show'][] = [
            'parameters' => [
                'slug' => $product->translate()->getSlug()
            ],
            'title' => $product->translate()->getTitle(),
        ];

        return $this->render('product/index.html.twig', [
            'seo' => $seo,
            'product' => $product,
            'staticContent' => $staticContent,
            'breadcrumbs' => $breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr),
        ]);
    }
}
