<?php

namespace Ecommerce\Controller\Frontend;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\TranslatorInterface;
use Ecommerce\Entity\ProductCategory;
use Ecommerce\Entity\Product;
use SeoBundle\Entity\SeoPage;
use ComponentBundle\Utils\BreadcrumbsGenerator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

        return $this->render('product_category/index.html.twig', [
            'seo' => $seo,
            'filter' => $filter,
            'category' => $category,
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

    private function renderFilter(bool $for_category = false)
    {
        $filter = $this->get('session')->get('filter');

        $categories = $this->em->getRepository(ProductCategory::class)->getForFilter($filter);
        $prices = $this->em->getRepository(Product::class)->getPricesForFilter($filter);

        return $this->renderView('filter/index.html.twig', [
            'capsule' => $filter['capsule'] ?? null,
            'category' => $filter['category'] ?? null,
            'categories' => $for_category ? null : $categories,
            'prices' => $prices,
            'filter' => $filter,
        ]);
    }
}
