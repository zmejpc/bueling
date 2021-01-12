<?php

namespace Ecommerce\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use StaticBundle\Entity\StaticContent;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\ActivityArea;
use SeoBundle\Entity\SeoPage;

/**
 * @author Design studio origami <https://origami.ua>
 */
final class ProductCategoryController extends AbstractController
{
    private $breadcrumbsGenerator;
    private $em;

    public function __construct(BreadcrumbsGenerator $breadcrumbsGenerator, EntityManagerInterface $em)
    {
        $this->breadcrumbsGenerator = $breadcrumbsGenerator;
        $this->em = $em;
    }

    public function showCategoryAction(TranslatorInterface $translator, string $slug, Request $request)
    {
        $category = $this->em->getRepository(ProductCategory::class)->getProductCategoryBySlug($slug);

        if(!$category) {
            throw $this->createNotFoundException($translator->trans('ui.notFound', [], 'FrontendBundle'));
        }

        $seo = $category->getSeo()->getSeoForPage();
        $staticContent = $this->em->getRepository(StaticContent::class)->getByPageForFrontend('category');
        $activityAreas = $this->em->getRepository(ActivityArea::class)->getForFrontend(8);

        return $this->render('product_category/show.html.twig', [
            'seo' => $seo,
            'category' => $category,
            'activityAreas' => $activityAreas,
            'staticContent' => $staticContent,
            'breadcrumbs' => $this->generateBreadcrumbs($category, 'frontend_show_product_category'),
        ]);
    }

    private function generateBreadcrumbs($object, string $route)
    {
        $breadcrumbsArr = [];
        $seoHomepage = $this->em->getRepository(SeoPage::class)->getSeoForPageBySystemName('homepage');
        $breadcrumbsArr['frontend_homepage'][] = [
            'parameters' => [],
            'title' => (!empty($seoHomepage) and !empty($seoHomepage->breadcrumb)) ? $seoHomepage->breadcrumb : ''
        ];

        $breadcrumbsArr[$route][] = [
            'parameters' => [
                'slug' => $object->translate()->getSlug()
            ],
            'title' => $object->translate()->getTitle(),
        ];

        return $this->breadcrumbsGenerator->generateBreadcrumbs($breadcrumbsArr);
    }
}
